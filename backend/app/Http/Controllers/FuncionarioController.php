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
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class FuncionarioController extends Controller
{
    /**
     * Mostra a lista de funcionários.
     */
    public function index()
    {
        // Busca todas as pessoas que têm o papel de 'Administrador' OU 'Consultor'
        $funcionarios = Pessoa::whereHas('roles', function ($query) {
            $query->whereIn('name', ['Administrador', 'Consultor']);
        })
        ->with('roles')
        ->where('id', '!=', 1) // Opcional: não mostrar o Admin Master na lista
        ->paginate(15);

        return view('funcionarios.index', compact('funcionarios'));
    }

    /**
     * Mostra o formulário para criar um novo funcionário.
     */
    public function create()
    {
        return view('funcionarios.create');
    }

    /**
     * Guarda um novo funcionário e os seus dados relacionados.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            // Dados Pessoais
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|max:20|unique:pessoa,cpf',
            'rg' => 'nullable|string|max:20',
            'nascimento' => 'required|date',
            // Contato e Endereço
            'telefone' => 'nullable|string|max:20',
            'logradouro' => 'required|string|max:255',
            'numero' => 'nullable|string|max:50',
            'cep' => 'nullable|string|max:20',
            'bairro' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'estado' => 'required|string|max:255',
            // Dados Profissionais e de Acesso
            'salario' => 'nullable|numeric|min:0',
            'data_contratacao' => 'required|date',
            'email' => 'required|email|unique:pessoa,email',
            'password' => ['required', Password::min(8)],
            'role' => ['required', Rule::in(['Administrador', 'Consultor'])],
        ]);

        // Usa uma transaction para garantir que tudo seja salvo junto, ou nada seja salvo.
        DB::transaction(function () use ($validatedData) {
            // 1. Cria ou encontra Estado, Cidade e Endereço
            $estado = Estado::firstOrCreate(['nome' => $validatedData['estado']]);
            $cidade = Cidade::firstOrCreate(['nome' => $validatedData['cidade'], 'estado_id' => $estado->id]);
            $endereco = Endereco::create([
                'logradouro' => $validatedData['logradouro'],
                'numero' => $validatedData['numero'],
                'bairro' => $validatedData['bairro'],
                'cep' => $validatedData['cep'],
                'cidade_id' => $cidade->id
            ]);

            // 2. Cria a Pessoa
            $pessoa = Pessoa::create([
                'nome' => $validatedData['nome'],
                'cpf' => $validatedData['cpf'],
                'rg' => $validatedData['rg'],
                'nascimento' => $validatedData['nascimento'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'endereco_id' => $endereco->id,
            ]);

            // 3. Cria o Telefone, se fornecido
            if (!empty($validatedData['telefone'])) {
                Telefone::create(['pessoa_id' => $pessoa->id, 'numero' => $validatedData['telefone']]);
            }
            
            // 4. Cria o registo de Funcionário
            Funcionario::create([
                'id' => $pessoa->id, // Usa o mesmo ID da pessoa
                'nivel_acesso' => $validatedData['role'],
                'salario' => $validatedData['salario'],
                'data_contratacao' => $validatedData['data_contratacao'],
            ]);

            // 5. Atribui o papel (Cargo)
            $pessoa->assignRole($validatedData['role']);
        });

        return redirect()->route('web.funcionarios.index')->with('success', 'Funcionário cadastrado com sucesso!');
    }

    /**
     * Mostra o formulário para editar um funcionário.
     */
    public function edit(Pessoa $funcionario)
    {
        return view('funcionarios.edit', compact('funcionario'));
    }

    /**
     * Atualiza os dados do funcionário.
     */
    public function update(Request $request, Pessoa $funcionario)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', Rule::unique('pessoa')->ignore($funcionario->id)],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $funcionario->email = $validated['email'];
        if (!empty($validated['password'])) {
            $funcionario->password = Hash::make($validated['password']);
        }
        $funcionario->save();

        return redirect()->route('web.funcionarios.index')->with('success', 'Funcionário atualizado com sucesso!');
    }

    /**
     * Apaga um funcionário.
     */
    public function destroy(Pessoa $funcionario)
    {
        // Regra de segurança para proteger o Admin Master
        if (Auth::user()->id !== 1 || $funcionario->id === 1) {
            return redirect()->route('web.funcionarios.index')->with('error', 'Ação não permitida.');
        }

        $funcionario->delete();

        return redirect()->route('web.funcionarios.index')->with('success', 'Funcionário excluído com sucesso.');
    }
}
