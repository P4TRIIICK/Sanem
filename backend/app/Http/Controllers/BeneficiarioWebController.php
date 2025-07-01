<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pessoa;
use App\Models\Beneficiario;
use App\Models\Endereco;
use App\Models\Cidade;
use App\Models\Estado;
use App\Models\Telefone;
use App\Models\Doacao;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Rules\ValidarCpf;
use Carbon\Carbon;

class BeneficiarioWebController extends Controller
{
    /**
     * Mostra a lista de beneficiários.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $query = Pessoa::whereHas('beneficiario')->with('beneficiario');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('cpf', 'like', "%{$search}%");
            });
        }
        $beneficiarios = $query->orderBy('nome', 'asc')->paginate(15);
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
            'cpf' => ['required', 'string', 'max:20', new ValidarCpf, 'unique:pessoa,cpf'],
            'rg' => 'nullable|string|max:20',
            'genero' => 'required|in:MASCULINO,FEMININO,OUTRO',
            'nascimento' => 'required|date',
            'renda' => 'nullable|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'logradouro' => 'required_if:possui_endereco,on|nullable|string|max:255',
            'bairro' => 'required_if:possui_endereco,on|nullable|string|max:255',
            'cidade' => 'required_if:possui_endereco,on|nullable|string|max:255',
            'estado' => 'required_if:possui_endereco,on|nullable|string|max:255',
            'numero' => 'nullable|string|max:50',
            'cep' => 'nullable|string|max:10',
        ]);

        DB::transaction(function () use ($request, $validatedData) {
            $cpfLimpo = preg_replace('/[^0-9]/', '', $validatedData['cpf']);
            $enderecoId = null;

            if ($request->has('possui_endereco')) {
                $estado = Estado::firstOrCreate(['nome' => $validatedData['estado']]);
                $cidade = Cidade::firstOrCreate(['nome' => $validatedData['cidade'], 'estado_id' => $estado->id]);
                $endereco = Endereco::create([
                    'logradouro' => $validatedData['logradouro'],
                    'numero' => $validatedData['numero'],
                    'bairro' => $validatedData['bairro'],
                    'cep' => $validatedData['cep'],
                    'cidade_id' => $cidade->id
                ]);
                $enderecoId = $endereco->id;
            }

            $pessoa = Pessoa::create([
                'nome' => $validatedData['nome'],
                'cpf' => $cpfLimpo,
                'rg' => $validatedData['rg'],
                'genero' => $validatedData['genero'],
                'nascimento' => $validatedData['nascimento'],
                'email' => $cpfLimpo . '@sanem.system',
                'password' => Hash::make(Str::random(10)),
                'tipo_beneficiario' => 'BENEFICIARIO',
                'endereco_id' => $enderecoId,
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
        $pessoa->load('beneficiario', 'endereco.cidade.estado');
        return view('beneficiarios.approval', compact('pessoa'));
    }

    /**
     * Mostra o formulário para editar um beneficiário.
     */
    public function edit(Pessoa $pessoa)
    {
        $pessoa->load('beneficiario', 'endereco.cidade.estado');
        return view('beneficiarios.edit', compact('pessoa'));
    }

    /**
     * Atualiza um beneficiário e os seus dados relacionados.
     */
    public function update(Request $request, Pessoa $pessoa)
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => ['required', new ValidarCpf, Rule::unique('pessoa')->ignore($pessoa->id)],
            'rg' => 'nullable|string|max:20',
            'genero' => 'required|in:MASCULINO,FEMININO,OUTRO',
            'nascimento' => 'required|date',
            'renda' => 'nullable|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'logradouro' => 'required_if:possui_endereco,on|nullable|string|max:255',
            'bairro' => 'required_if:possui_endereco,on|nullable|string|max:255',
            'cidade' => 'required_if:possui_endereco,on|nullable|string|max:255',
            'estado' => 'required_if:possui_endereco,on|nullable|string|max:255',
            'numero' => 'nullable|string|max:50',
            'cep' => 'nullable|string|max:10',
        ]);

        DB::transaction(function () use ($request, $validatedData, $pessoa) {
            
            $enderecoId = $pessoa->endereco_id;
            $enderecoAntigo = $pessoa->endereco;

            if ($request->has('possui_endereco')) {
                $estado = Estado::firstOrCreate(['nome' => $validatedData['estado']]);
                $cidade = Cidade::firstOrCreate(['nome' => $validatedData['cidade'], 'estado_id' => $estado->id]);
                
                $endereco = Endereco::updateOrCreate(
                    ['id' => $enderecoId],
                    [
                        'logradouro' => $validatedData['logradouro'],
                        'numero' => $validatedData['numero'],
                        'bairro' => $validatedData['bairro'],
                        'cep' => $validatedData['cep'],
                        'cidade_id' => $cidade->id
                    ]
                );
                $enderecoId = $endereco->id;
            } else {
                $enderecoId = null;
            }

            $fotoPath = $pessoa->beneficiario?->foto_path;
            if ($request->hasFile('foto')) {
                if ($fotoPath) {
                    Storage::disk('public')->delete($fotoPath);
                }
                $fotoPath = $request->file('foto')->store('fotos_beneficiarios', 'public');
            }

            $pessoa->update([
                'nome' => $validatedData['nome'],
                'cpf' => preg_replace('/[^0-9]/', '', $validatedData['cpf']),
                'rg' => $validatedData['rg'],
                'genero' => $validatedData['genero'],
                'nascimento' => $validatedData['nascimento'],
                'endereco_id' => $enderecoId,
            ]);
            
            if (!$request->has('possui_endereco') && $enderecoAntigo) {
                $enderecoAntigo->delete();
            }

            if ($pessoa->beneficiario) {
                $pessoa->beneficiario->update([
                    'renda' => $validatedData['renda'] ?? null,
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
        if ($pessoa->beneficiario?->foto_path) {
            Storage::disk('public')->delete($pessoa->beneficiario->foto_path);
        }
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

    /**
     * Procura por beneficiários com base num termo de pesquisa (nome ou CPF).
     * Retorna os resultados em formato JSON para uso com AJAX/JavaScript.
     */
    public function search(Request $request)
    {
        $term = $request->query('term');

        if (empty($term)) {
            return response()->json([]);
        }

        $cpfLimpo = preg_replace('/[^0-9]/', '', $term);

        // Busca apenas beneficiários com status APROVADO
        $beneficiarios = Pessoa::whereHas('beneficiario', function ($q) {
                $q->where('status', 'APROVADO');
            })
            ->where(function ($query) use ($term, $cpfLimpo) {
                $query->where('nome', 'like', "%{$term}%")
                      ->orWhere('cpf', 'like', "%{$cpfLimpo}%");
            })
            ->limit(10)
            ->get(['id', 'nome', 'cpf']);

        // Para cada beneficiário encontrado, calcula o total de itens já recebidos no mês atual
        $beneficiarios->each(function ($pessoa) {
             $totalItens = DB::table('doacoes')
                ->join('doacao_item', 'doacoes.id', '=', 'doacao_item.doacao_id')
                ->where('doacoes.pessoa_id', $pessoa->id)
                ->whereMonth('doacoes.data_doacao', now()->month)
                ->whereYear('doacoes.data_doacao', now()->year)
                ->sum('doacao_item.quantidade_doada');
            
            $pessoa->total_itens_mes = $totalItens;
        });

        return response()->json($beneficiarios);
    }
}
