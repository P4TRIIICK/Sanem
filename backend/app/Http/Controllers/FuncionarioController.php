<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pessoa;
use App\Models\Endereco;
use App\Models\Telefone;
use App\Models\Cidade;
use App\Models\Estado;
use App\Models\Funcionario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Rules\ValidarCpf;

class FuncionarioController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        // Começa a query base para buscar funcionários
        $query = Pessoa::whereHas('roles', function ($query) {
            $query->whereIn('name', ['Administrador', 'Consultor']);
        })->with('roles')->where('id', '!=', 1);

        // Se houver um termo de pesquisa, adiciona a condição de filtro
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $funcionarios = $query->paginate(15);

        return view('funcionarios.index', compact('funcionarios'));
    }

    /**
     * Mostra o formulário para criar um novo funcionário.
     */
    public function create()
    {
        return view('funcionarios.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|max:20|unique:pessoa,cpf',
            'rg' => 'nullable|string|max:20',
            'nascimento' => 'required|date',
            'telefone' => 'nullable|string|max:20',
            'logradouro' => 'required|string|max:255',
            'numero' => 'nullable|string|max:50',
            'cep' => 'nullable|string|max:20',
            'bairro' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'estado' => 'required|string|max:255',
            'salario' => 'nullable|numeric|min:0',
            'data_contratacao' => 'required|date',
            'email' => 'required|email|unique:pessoa,email',
            'password' => ['required', Password::min(8)],
            'role' => ['required', Rule::in(['Administrador', 'Consultor'])],
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validação da foto
        ]);

        DB::transaction(function () use ($request, $validatedData) {
            $fotoPath = null;
            // 1. Guarda a foto, se ela foi enviada
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('fotos_funcionarios', 'public');
            }

            $estado = Estado::firstOrCreate(['nome' => $validatedData['estado']]);
            $cidade = Cidade::firstOrCreate(['nome' => $validatedData['cidade'], 'estado_id' => $estado->id]);
            $endereco = Endereco::create([
                'logradouro' => $validatedData['logradouro'],
                'numero' => $validatedData['numero'],
                'bairro' => $validatedData['bairro'],
                'cep' => $validatedData['cep'],
                'cidade_id' => $cidade->id
            ]);

            // 2. Cria a Pessoa, incluindo o caminho da foto
            $pessoa = Pessoa::create([
                'nome' => $validatedData['nome'],
                'cpf' => preg_replace('/[^0-9]/', '', $validatedData['cpf']), // Guarda só os números
                'rg' => $validatedData['rg'],
                'nascimento' => $validatedData['nascimento'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'endereco_id' => $endereco->id,
                'foto_path' => $fotoPath, // Guarda o caminho da foto
            ]);

            if (!empty($validatedData['telefone'])) {
                Telefone::create(['pessoa_id' => $pessoa->id, 'numero' => $validatedData['telefone']]);
            }
            
            Funcionario::create([
                'id' => $pessoa->id,
                'nivel_acesso' => $validatedData['role'],
                'salario' => $validatedData['salario'],
                'data_contratacao' => $validatedData['data_contratacao'],
            ]);

            $pessoa->assignRole($validatedData['role']);
        });

        return redirect()->route('web.funcionarios.index')->with('success', 'Funcionário cadastrado com sucesso!');
    }
    
    public function show(Pessoa $funcionario)
    {
        if (auth()->user()->id !== 1) {
            abort(403, 'Ação não autorizada.');
        }
        $funcionario->load(['endereco.cidade.estado', 'telefones', 'funcionario']);
        return view('funcionarios.show', compact('funcionario'));
    }

    public function edit(Pessoa $funcionario)
    {
        $funcionario->load(['endereco.cidade.estado', 'telefones', 'funcionario']);
        return view('funcionarios.edit', compact('funcionario'));
    }

    /**
     * Atualiza um funcionário existente com todos os seus dados.
     */
    public function update(Request $request, Pessoa $funcionario)
    {
        $validatedData = $request->validate([
            // Validação de todos os campos do formulário de edição
            'nome' => 'required|string|max:255',
            'cpf' => ['required', new ValidarCpf, Rule::unique('pessoa')->ignore($funcionario->id)],
            'rg' => 'nullable|string|max:20',
            'nascimento' => 'required|date',
            'telefone' => 'nullable|string|max:20',
            'logradouro' => 'required|string|max:255',
            'numero' => 'nullable|string|max:50',
            'cep' => 'nullable|string|max:20',
            'bairro' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'estado' => 'required|string|max:255',
            'salario' => 'nullable|numeric|min:0',
            'data_contratacao' => 'required|date',
            'email' => ['required', 'email', Rule::unique('pessoa')->ignore($funcionario->id)],
            'password' => ['nullable', Password::min(8)], // A senha é opcional na edição
            'role' => ['required', Rule::in(['Administrador', 'Consultor'])],
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::transaction(function () use ($request, $validatedData, $funcionario) {
            
            // 1. Lida com o upload da foto
            $fotoPath = $funcionario->foto_path;
            if ($request->hasFile('foto')) {
                // Apaga a foto antiga, se existir
                if ($fotoPath) {
                    Storage::disk('public')->delete($fotoPath);
                }
                // Guarda a nova foto e atualiza o caminho
                $fotoPath = $request->file('foto')->store('fotos_funcionarios', 'public');
            }

            // 2. Atualiza o endereço
            $estado = Estado::firstOrCreate(['nome' => $validatedData['estado']]);
            $cidade = Cidade::firstOrCreate(['nome' => $validatedData['cidade'], 'estado_id' => $estado->id]);
            // Usa updateOrCreate para atualizar o endereço existente ou criar um novo se não existir
            $funcionario->endereco()->updateOrCreate(
                ['id' => $funcionario->endereco_id],
                [
                    'logradouro' => $validatedData['logradouro'],
                    'numero' => $validatedData['numero'],
                    'bairro' => $validatedData['bairro'],
                    'cep' => $validatedData['cep'],
                    'cidade_id' => $cidade->id
                ]
            );

            // 3. Prepara os dados da Pessoa
            $pessoaData = $validatedData;
            $pessoaData['cpf'] = preg_replace('/[^0-9]/', '', $validatedData['cpf']);
            $pessoaData['foto_path'] = $fotoPath;

            // Lida com a atualização da senha (só se uma nova foi fornecida)
            if (!empty($validatedData['password'])) {
                $pessoaData['password'] = Hash::make($validatedData['password']);
            } else {
                unset($pessoaData['password']); // Remove a senha do array se estiver vazia
            }
            
            // 4. Atualiza a Pessoa
            $funcionario->update($pessoaData);

            // 5. Atualiza o Telefone
            if (!empty($validatedData['telefone'])) {
                $funcionario->telefones()->updateOrCreate(['pessoa_id' => $funcionario->id], ['numero' => $validatedData['telefone']]);
            } else {
                $funcionario->telefones()->delete(); // Apaga o telefone se o campo for deixado em branco
            }

            // 6. Atualiza os dados do Funcionário
            $funcionario->funcionario()->updateOrCreate(
                ['id' => $funcionario->id],
                [
                    'nivel_acesso' => $validatedData['role'],
                    'salario' => $validatedData['salario'],
                    'data_contratacao' => $validatedData['data_contratacao'],
                ]
            );

            // 7. Sincroniza o papel (cargo)
            $funcionario->syncRoles([$validatedData['role']]);
        });

        return redirect()->route('web.funcionarios.index')->with('success', 'Funcionário atualizado com sucesso!');
    }

    public function destroy(Pessoa $funcionario)
    {
        if (Auth::user()->id !== 1 || $funcionario->id === 1) {
            return redirect()->route('web.funcionarios.index')->with('error', 'Ação não permitida.');
        }

        // Apaga a foto antiga do storage antes de apagar o registo
        if ($funcionario->foto_path) {
            Storage::disk('public')->delete($funcionario->foto_path);
        }

        $funcionario->delete();

        return redirect()->route('web.funcionarios.index')->with('success', 'Funcionário excluído com sucesso.');
    }
}
