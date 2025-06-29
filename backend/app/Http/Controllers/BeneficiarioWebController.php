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

class BeneficiarioWebController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $beneficiarios = Pessoa::whereHas('beneficiario')->with('beneficiario')->orderBy('nome', 'asc')->paginate(15);
        return view('beneficiarios.index', compact('beneficiarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('beneficiarios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|max:20|unique:pessoa,cpf',
            'rg' => 'nullable|string|max:20',
            'genero' => 'required|in:MASCULINO,FEMININO,OUTRO',
            'nascimento' => 'required|date',
            'renda' => 'nullable|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::transaction(function () use ($request, $validatedData) {
            $pessoa = Pessoa::create([
                'nome' => $validatedData['nome'],
                'cpf' => $validatedData['cpf'],
                'rg' => $validatedData['rg'],
                'genero' => $validatedData['genero'],
                'nascimento' => $validatedData['nascimento'],
                'email' => $validatedData['cpf'] . '@sanem.system',
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

            // Reativado: Atribui o papel de 'Beneficiario' à pessoa criada
            $pessoa->assignRole('Beneficiario');
        });

        return redirect()->route('web.beneficiarios.index')->with('success', 'Beneficiário cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pessoa $pessoa)
    {
        $pessoa->load('beneficiario');
        // Reutiliza a view de aprovação para mostrar os detalhes
        return view('beneficiarios.approval', compact('pessoa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pessoa $pessoa)
    {
        $pessoa->load('beneficiario');
        return view('beneficiarios.edit', compact('pessoa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pessoa $pessoa)
    {
        $validatedData = $request->validate([
            'nome'       => 'required|string|max:255',
            'cpf'        => ['required', 'string', 'max:20', Rule::unique('pessoa')->ignore($pessoa->id)],
            'rg'         => 'nullable|string|max:20',
            'genero'     => 'required|in:MASCULINO,FEMININO,OUTRO',
            'nascimento' => 'required|date',
            'renda'      => 'nullable|numeric|min:0',
            'foto'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::transaction(function () use ($request, $validatedData, $pessoa) {
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
     * Remove the specified resource from storage.
     */
    public function destroy(Pessoa $pessoa)
    {
        $pessoa->delete();
        return redirect()->route('web.beneficiarios.index')->with('success', 'Beneficiário excluído com sucesso.');
    }

    /**
     * Show the form for managing the beneficiary's status.
     */
    public function showApprovalForm(Pessoa $pessoa)
    {
        $pessoa->load('beneficiario');
        return view('beneficiarios.approval', compact('pessoa'));
    }

    /**
     * Process the approval or rejection of the beneficiary.
     */
    public function processApproval(Request $request, Pessoa $pessoa)
    {
        $request->validate([
            'status' => ['required', Rule::in(['APROVADO', 'REPROVADO'])],
        ]);

        if ($pessoa->beneficiario) {
            $pessoa->beneficiario->update(['status' => $request->status]);
            return redirect()->route('web.beneficiarios.index')->with('success', 'Status do beneficiário atualizado!');
        }

        return redirect()->route('web.beneficiarios.index')->with('error', 'Beneficiário não encontrado.');
    }
}
