<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pessoa; // Importa o modelo Pessoa para que possamos usá-lo

class BeneficiarioWebController extends Controller
{
    /**
     * Exibe a página de listagem de beneficiários.
     * Este método é responsável por buscar os dados do banco e
     * passá-los para o arquivo Blade correspondente.
     */
    public function index()
    {
        // Busca os registros mais recentes do modelo Pessoa no banco de dados.
        // O método paginate(15) divide o resultado em páginas com 15 itens cada,
        // o que é uma boa prática para performance.
        $beneficiarios = Pessoa::latest()->paginate(15);

        // Retorna a view localizada em 'resources/views/beneficiarios/index.blade.php'
        // e passa a variável 'beneficiarios' (que contém os dados) para a view.
        return view('beneficiarios.index', [
            'beneficiarios' => $beneficiarios
        ]);
    }

    /**
     * Exibe a página com o formulário para criar um novo beneficiário.
     * Atualmente, esta rota existe, mas a view ainda não foi criada.
     */
    public function create()
    {
        return view('beneficiarios.create');
    }

    /**
     * Armazena um novo beneficiário no banco de dados.
     * Este método será chamado pelo formulário de criação.
     * (Ainda não implementado)
     */
    public function store(Request $request)
    {
        // dd() significa "Dump and Die". Ele vai mostrar na tela todos os dados
        // que o formulário enviou através do $request e vai parar a execução.
        // É a melhor ferramenta para debugar o que está chegando aqui.
        dd($request->all());
    }

    /**
     * Exibe os detalhes de um beneficiário específico.
     * (Ainda não implementado)
     */
    public function show(Pessoa $pessoa)
    {
        // Lógica para mostrar a página de detalhes virá aqui.
    }

    /**
     * Exibe o formulário para editar um beneficiário existente.
     * (Ainda não implementado)
     */
    public function edit(Pessoa $pessoa)
    {
        // Lógica para mostrar o formulário de edição virá aqui.
    }

    /**
     * Atualiza um beneficiário existente no banco de dados.
     * (Ainda não implementado)
     */
    public function update(Request $request, Pessoa $pessoa)
    {
        // Lógica para validar e atualizar os dados virá aqui.
    }

    /**
     * Remove um beneficiário do banco de dados.
     * (Ainda não implementado)
     */
    public function destroy(Pessoa $pessoa)
    {
        // Lógica para deletar o beneficiário virá aqui.
    }
}