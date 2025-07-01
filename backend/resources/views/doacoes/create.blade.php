@extends('layouts.app')

@section('title', 'Registrar Nova Doação')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary me-3" title="Voltar ao Dashboard">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="h2 mb-0">Registrar Nova Doação</h1>
    </div>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- O formulário principal que será submetido --}}
    <form action="{{ route('web.doacoes.store') }}" method="POST" id="form-doacao">
        @csrf
        <input type="hidden" id="pessoa_id" name="pessoa_id">
        <div id="itens_hidden_inputs"></div>

        {{-- Card 1: Seleção do Beneficiário --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0"><span class="badge bg-secondary me-2">1</span> Identificar o Beneficiário</h5>
            </div>
            <div class="card-body">
                <div class="position-relative">
                    <label for="beneficiario_busca" class="form-label">Digite o nome ou CPF do beneficiário:</label>
                    <input type="text" class="form-control form-control-lg" id="beneficiario_busca" placeholder="Aguardando busca..." autocomplete="off">
                    <div id="beneficiario_resultados" class="list-group position-absolute w-100" style="z-index: 1000;"></div>
                </div>
                <div id="beneficiario_info" class="mt-3 alert alert-success" style="display: none;"></div>
            </div>
        </div>

        {{-- Card 2: Seleção de Itens (só aparece depois do passo 1) --}}
        <div id="selecao_itens_card" class="card border-0 shadow-sm mb-4" style="display: none;">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0"><span class="badge bg-secondary me-2">2</span> Adicionar Itens à Doação</h5>
            </div>
            <div class="card-body">
                <div class="position-relative">
                    <label for="item_busca" class="form-label">Pesquisar item no estoque:</label>
                    <input type="text" class="form-control" id="item_busca" placeholder="Digite o nome do item..." autocomplete="off">
                    <div id="item_resultados" class="list-group position-absolute w-100" style="z-index: 999;"></div>
                </div>

                <h6 class="mt-4">Itens na Cesta de Doação</h6>
                <div class="table-responsive">
                    <table class="table" id="tabela_cesta">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th style="width: 120px;">Quantidade</th>
                                <th style="width: 50px;">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Itens adicionados aparecerão aqui via JS --}}
                        </tbody>
                    </table>
                </div>
                <div id="limite_aviso" class="alert alert-warning mt-3" style="display: none;"></div>
            </div>
        </div>

        {{-- Card 3: Finalizar --}}
        <div id="finalizar_card" class="card border-0 shadow-sm" style="display: none;">
             <div class="card-body text-end">
                <button type="submit" id="btn_finalizar" class="btn btn-primary btn-lg" style="background-color: var(--cor-primaria); border-color: var(--cor-primaria);">
                    <i class="bi bi-check-circle-fill me-2"></i>Finalizar e Registrar Doação
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elementos do DOM
    const beneficiarioBusca = document.getElementById('beneficiario_busca');
    const beneficiarioResultados = document.getElementById('beneficiario_resultados');
    const beneficiarioInfo = document.getElementById('beneficiario_info');
    const pessoaIdInput = document.getElementById('pessoa_id');
    const selecaoItensCard = document.getElementById('selecao_itens_card');
    const finalizarCard = document.getElementById('finalizar_card');
    const itemBusca = document.getElementById('item_busca');
    const itemResultados = document.getElementById('item_resultados');
    const tabelaCestaBody = document.querySelector('#tabela_cesta tbody');
    const itensHiddenInputs = document.getElementById('itens_hidden_inputs');
    const formDoacao = document.getElementById('form-doacao');
    const limiteAviso = document.getElementById('limite_aviso');

    let cesta = [];
    let totalItensMes = 0;
    const LIMITE_MENSAL = 20;

    function debounce(func, delay) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    }

    // Busca de Beneficiários
    beneficiarioBusca.addEventListener('keyup', debounce(function() {
        const termo = this.value;
        if (termo.length < 3) {
            beneficiarioResultados.innerHTML = '';
            return;
        }
        fetch(`{{ route('web.beneficiarios.search') }}?term=${termo}`)
            .then(response => response.json())
            .then(data => {
                beneficiarioResultados.innerHTML = '';
                if (data && data.length > 0) {
                    data.forEach(pessoa => {
                        const item = document.createElement('a');
                        item.href = '#';
                        item.className = 'list-group-item list-group-item-action';
                        item.innerHTML = `${pessoa.nome} <small class="text-muted">(${pessoa.cpf})</small>`;
                        item.addEventListener('click', e => {
                            e.preventDefault();
                            selecionarBeneficiario(pessoa);
                        });
                        beneficiarioResultados.appendChild(item);
                    });
                } else {
                    beneficiarioResultados.innerHTML = '<span class="list-group-item">Nenhum beneficiário encontrado.</span>';
                }
            });
    }, 300));

    function selecionarBeneficiario(pessoa) {
        beneficiarioBusca.value = pessoa.nome;
        beneficiarioInfo.innerHTML = `<strong>Beneficiário Selecionado:</strong> ${pessoa.nome} (CPF: ${pessoa.cpf})`;
        beneficiarioInfo.style.display = 'block';
        pessoaIdInput.value = pessoa.id;
        beneficiarioResultados.innerHTML = '';
        
        totalItensMes = pessoa.total_itens_mes || 0;
        
        selecaoItensCard.style.display = 'block';
        finalizarCard.style.display = 'block';
        atualizarAvisoLimite();
    }

    // Busca de Itens
    itemBusca.addEventListener('keyup', debounce(function() {
        const termo = this.value;
        if (termo.length < 2) {
            itemResultados.innerHTML = '';
            return;
        }
        fetch(`{{ route('web.itens.search') }}?term=${termo}`)
            .then(response => response.json())
            .then(data => {
                itemResultados.innerHTML = '';
                if (data.length > 0) {
                    data.forEach(item => {
                        const el = document.createElement('a');
                        el.href = '#';
                        el.className = 'list-group-item list-group-item-action';
                        el.innerHTML = `${item.nome_item} <span class="badge bg-secondary float-end">Qtd: ${item.quantidade}</span>`;
                        el.addEventListener('click', e => {
                            e.preventDefault();
                            adicionarItemNaCesta(item);
                            itemBusca.value = '';
                            itemResultados.innerHTML = '';
                        });
                        itemResultados.appendChild(el);
                    });
                } else {
                    itemResultados.innerHTML = '<span class="list-group-item">Nenhum item encontrado.</span>';
                }
            });
    }, 300));

    function adicionarItemNaCesta(item) {
        const itemExistente = cesta.find(i => i.id === item.id);
        if (itemExistente) {
            if (itemExistente.quantidade < 3) {
                itemExistente.quantidade++;
            } else {
                alert('Limite de 3 unidades por item atingido.');
            }
        } else {
            cesta.push({ id: item.id, nome: item.nome_item, quantidade: 1 });
        }
        renderizarCesta();
    }

    function renderizarCesta() {
        tabelaCestaBody.innerHTML = '';
        cesta.forEach((item, index) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${item.nome}</td>
                <td><input type="number" class="form-control form-control-sm" value="${item.quantidade}" min="1" max="3" data-index="${index}"></td>
                <td><button type="button" class="btn btn-sm btn-danger" data-index="${index}"><i class="bi bi-trash-fill"></i></button></td>
            `;
            tabelaCestaBody.appendChild(tr);
        });
        atualizarAvisoLimite();
    }
    
    tabelaCestaBody.addEventListener('change', function(e) {
        if (e.target.tagName === 'INPUT') {
            const index = e.target.dataset.index;
            let novaQtd = parseInt(e.target.value);
            if (novaQtd > 3) {
                novaQtd = 3;
                e.target.value = 3;
                alert('Limite de 3 unidades por item.');
            }
            cesta[index].quantidade = novaQtd;
            atualizarAvisoLimite();
        }
    });

    tabelaCestaBody.addEventListener('click', function(e) {
        if (e.target.closest('button')) {
            const index = e.target.closest('button').dataset.index;
            cesta.splice(index, 1);
            renderizarCesta();
        }
    });
    
    function atualizarAvisoLimite() {
        const totalNaCesta = cesta.reduce((acc, item) => acc + item.quantidade, 0);
        const totalGeral = totalItensMes + totalNaCesta;
        
        if (totalGeral > LIMITE_MENSAL) {
            limiteAviso.innerHTML = `<strong>Atenção:</strong> Limite mensal de ${LIMITE_MENSAL} itens excedido! Total com esta doação: ${totalGeral}.`;
            limiteAviso.style.display = 'block';
        } else {
            limiteAviso.style.display = 'none';
        }
    }

    formDoacao.addEventListener('submit', function() {
        itensHiddenInputs.innerHTML = '';
        cesta.forEach((item, index) => {
            itensHiddenInputs.innerHTML += `
                <input type="hidden" name="itens[${index}][item_id]" value="${item.id}">
                <input type="hidden" name="itens[${index}][quantidade]" value="${item.quantidade}">
            `;
        });
    });
});
</script>
@endpush
