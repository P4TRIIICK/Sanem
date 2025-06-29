<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pessoa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class PessoaController extends Controller
{
    public function index()
    {
        // Retorna todos os registros de Pessoa, pode ser otimizado com paginação se necessário
        return response()->json(Pessoa::with(['beneficiario', 'funcionario'])->latest()->get(), 200);
    }

    public function store(Request $request)
    {
        // 1. Validação dos dados recebidos do formulário
        $data = $request->validate([
            'nome'              => 'required|string|max:255',
            'cpf'               => 'required|string|max:20|unique:pessoa,cpf',
            'rg'                => 'nullable|string|max:20',
            'genero'            => 'required|in:MASCULINO,FEMININO,OUTRO',
            'tipo_beneficiario' => 'required|in:BENEFICIARIO,DOADOR,BENEFICIARIO_DOADOR',
            'nascimento'        => 'nullable|date',
            'email'             => 'nullable|email|max:255|unique:pessoa,email',
            'password'          => 'required|string|min:6',
        ]);

        try {
            // 2. Usar uma transação para garantir a integridade dos dados
            $pessoa = DB::transaction(function () use ($data) {
                
                // 3. Cria a Pessoa
                $pessoa = Pessoa::create($data);

                // 4. Se o tipo incluir 'BENEFICIARIO', cria o registro associado
                if (in_array($pessoa->tipo_beneficiario, ['BENEFICIARIO', 'BENEFICIARIO_DOADOR'])) {
                    $pessoa->beneficiario()->create([
                        'status_conta' => 'CONTA_EM_ANALISE' // Status inicial padrão
                    ]);
                    // Atribui o papel de Beneficiário
                    $pessoa->assignRole('Beneficiario');
                }

                // 5. Se o tipo incluir 'DOADOR', atribui o papel
                if (in_array($pessoa->tipo_beneficiario, ['DOADOR', 'BENEFICIARIO_DOADOR'])) {
                    $pessoa->assignRole('Doador');
                }
                
                return $pessoa;
            });

            // 6. Retorna a pessoa criada com sucesso
            return response()->json($pessoa, 201);

        } catch (\Exception $e) {
            // Em caso de erro, registra o log e retorna uma resposta de erro
            Log::error('Erro ao criar pessoa: ' . $e->getMessage());
            return response()->json(['error' => 'Ocorreu um erro interno ao criar o beneficiário.'], 500);
        }
    }
    
    /**
     * Exibe os dados de uma pessoa específica.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pessoa = Pessoa::with(['endereco', 'telefones', 'beneficiario', 'funcionario'])->find($id);

        if (!$pessoa) {
            return response()->json(['error' => 'Pessoa não encontrada'], 404);
        }

        return response()->json($pessoa, 200);
    }

    /**
     * Atualiza os dados de uma pessoa específica.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $pessoa = Pessoa::find($id);

        if (!$pessoa) {
            return response()->json(['error' => 'Pessoa não encontrada'], 404);
        }

        $data = $request->validate([
            'nome'              => 'sometimes|required|string|max:255',
            'cpf'               => ['sometimes', 'required', 'string', 'max:20', Rule::unique('pessoa')->ignore($id)],
            'rg'                => 'nullable|string|max:20',
            'genero'            => 'sometimes|required|in:MASCULINO,FEMININO,OUTRO',
            'tipo_beneficiario' => 'sometimes|required|in:BENEFICIARIO,DOADOR,BENEFICIARIO_DOADOR',
            'nascimento'        => 'nullable|date',
            'email'             => ['nullable', 'email', 'max:255', Rule::unique('pessoa')->ignore($id)],
            'password'          => 'nullable|string|min:6', // Senha se torna opcional na atualização
        ]);
        
        // Remove o campo de senha se ele estiver vazio para não sobrescrever
        if(empty($data['password'])) {
            unset($data['password']);
        }

        try {
            DB::transaction(function () use ($pessoa, $data) {
                $pessoa->update($data);

                // Lógica para atualizar papéis e tabelas relacionadas se o tipo mudar
                if (isset($data['tipo_beneficiario'])) {
                    // Sincroniza os papéis com base no novo tipo
                    $roles = [];
                    if (in_array($data['tipo_beneficiario'], ['BENEFICIARIO', 'BENEFICIARIO_DOADOR'])) {
                        $roles[] = 'Beneficiario';
                        // Garante que o registro de beneficiário exista
                        $pessoa->beneficiario()->firstOrCreate([], ['status_conta' => 'CONTA_EM_ANALISE']);
                    } else {
                        // Se não é mais beneficiário, remove o registro
                        $pessoa->beneficiario()->delete();
                    }

                    if (in_array($data['tipo_beneficiario'], ['DOADOR', 'BENEFICIARIO_DOADOR'])) {
                        $roles[] = 'Doador';
                    }

                    $pessoa->syncRoles($roles);
                }
            });

            return response()->json($pessoa->fresh(), 200);

        } catch (\Exception $e) {
            Log::error('Erro ao atualizar pessoa: ' . $e->getMessage());
            return response()->json(['error' => 'Ocorreu um erro interno ao atualizar o beneficiário.'], 500);
        }
    }

    /**
     * Remove uma pessoa do banco de dados.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pessoa = Pessoa::find($id);

        if (!$pessoa) {
            return response()->json(['error' => 'Pessoa não encontrada'], 404);
        }

        try {
            $pessoa->delete();
            // A deleção em cascata definida no banco de dados cuidará dos registros associados
            return response()->json(['message' => 'Pessoa excluída com sucesso'], 200);
        } catch (\Exception $e) {
             Log::error('Erro ao excluir pessoa: ' . $e->getMessage());
            return response()->json(['error' => 'Ocorreu um erro interno ao excluir o beneficiário.'], 500);
        }
    }
}
