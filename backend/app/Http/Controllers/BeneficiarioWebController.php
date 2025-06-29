<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pessoa;
use App\Models\Beneficiario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Rules\ValidarCpf; // <-- Importa a nova regra de validação

class BeneficiarioWebController extends Controller
{
    /**
     * Mostra a lista de beneficiários.
     */
    public function index()
    {
        $beneficiarios = Pessoa::whereHas('beneficiario')->with('beneficiario')->orderBy('nome', 'asc')->paginate(15);
        return view('beneficiarios.index', compact('beneficiarios'));
    }

    /**
     * Mostra o formulário para criar um novo beneficiário.
     */
    public function create()
    {
        return view('beneficiarios.create');
    }

    /**
     * Guarda um novo beneficiário na base de dados.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            // Usa a nova regra de validação para o CPF
            'cpf' => ['required', 'string', 'max:20', new ValidarCpf, 'unique:pessoa,cpf'],
            'rg' => 'nullable|string|max:20',
            'genero' => 'required|in:MASCULINO,FEMININO,OUTRO',
            'nascimento' => 'required|date',
            'renda' => 'nullable|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::transaction(function () use ($request, $validatedData) {
            // Limpa a formatação do CPF antes de guardar para garantir consistência
            $cpfLimpo = preg_replace('/[^0-9]/', '', $validatedData['cpf']);

            $pessoa = Pessoa::create([
                'nome' => $validatedData['nome'],
                'cpf' => $cpfLimpo, // Guarda apenas os números
                'rg' => $validatedData['rg'],
                'genero' => $validatedData['genero'],
                'nascimento' => $validatedData['nascimento'],
                'email' => $cpfLimpo . '@sanem.system',
                'password' => Hash::make(Str::random(10)),
                'tipo_beneficiario' => 'BENEFICIARIO',
                'endereco_id' => null,
            ]);

            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('fotos_beneficiarios', 'public');
            }

            Beneficiario::create([
                'pessoa_id' => $pessoa->id,
                'identificador_unico' => Str::uuid(),
                'renda' => $validatedData['renda'] ?? null,
                'foto_path' => $fotoPath,
                'status' => 'EM_ANALISE',
            ]);

            $pessoa->assignRole('Beneficiario');
        });

        return redirect()->route('web.beneficiarios.index')->with('success', 'Beneficiário cadastrado com sucesso!');
    }

    /**
     * Mostra os detalhes de um beneficiário.
     */
    public function show(Pessoa $pessoa)
    {
        $pessoa->load('beneficiario');
        return view('beneficiarios.approval', compact('pessoa'));
    }

    /**
     * Mostra o formulário para editar um beneficiário.
     */
    public function edit(Pessoa $pessoa)
    {
        $pessoa->load('beneficiario');
        return view('beneficiarios.edit', compact('pessoa'));
    }

    /**
     * Atualiza os dados de um beneficiário.
     */
    public function update(Request $request, Pessoa $pessoa)
    {
        $validatedData = $request->validate([
            'nome'       => 'required|string|max:255',
            // Usa a nova regra de validação também na atualização
            'cpf'        => ['required', 'string', 'max:20', new ValidarCpf, Rule::unique('pessoa')->ignore($pessoa->id)],
            'rg'         => 'nullable|string|max:20',
            'genero'     => 'required|in:MASCULINO,FEMININO,OUTRO',
            'nascimento' => 'required|date',
            'renda'      => 'nullable|numeric|min:0',
            'foto'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::transaction(function () use ($request, $validatedData, $pessoa) {
            // Limpa a formatação do CPF antes de atualizar
            $validatedData['cpf'] = preg_replace('/[^0-9]/', '', $validatedData['cpf']);
            
            $pessoa->update($validatedData);

            $fotoPath = $pessoa->beneficiario->foto_path ?? null;
            if ($request->hasFile('foto')) {
                if ($fotoPath) {
                    Storage::disk('public')->delete($fotoPath);
                }
                $fotoPath = $request->file('foto')->store('fotos_beneficiarios', 'public');
            }

            if ($pessoa->beneficiario) {
                $pessoa->beneficiario->update([
                    'renda'     => $validatedData['renda'] ?? null,
                    'foto_path' => $fotoPath,
                ]);
            }
        });

        return redirect()->route('web.beneficiarios.index')->with('success', 'Beneficiário atualizado com sucesso!');
    }

    /**
     * Remove um beneficiário.
     */
    public function destroy(Pessoa $pessoa)
    {
        $pessoa->delete();
        return redirect()->route('web.beneficiarios.index')->with('success', 'Beneficiário excluído com sucesso.');
    }

    /**
     * Mostra o formulário para gerir o status de um beneficiário.
     */
    public function showApprovalForm(Pessoa $pessoa)
    {
        $pessoa->load('beneficiario');
        return view('beneficiarios.approval', compact('pessoa'));
    }

    /**
     * Processa a aprovação/reprovação de um beneficiário.
     */
    public function processApproval(Request $request, Pessoa $pessoa)
    {
        $request->validate(['status' => ['required', Rule::in(['APROVADO', 'REPROVADO'])]]);

        if ($pessoa->beneficiario) {
            $pessoa->beneficiario->update(['status' => $request->status]);
            return redirect()->route('web.beneficiarios.index')->with('success', 'Status do beneficiário atualizado!');
        }

        return redirect()->route('web.beneficiarios.index')->with('error', 'Beneficiário não encontrado.');
    }
}
