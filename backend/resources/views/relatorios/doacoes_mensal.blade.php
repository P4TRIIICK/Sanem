<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Doações - {{ $mes }} de {{ $ano }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #fff; }
        .container { max-width: 960px; }
        .report-header { text-align: center; margin-bottom: 40px; padding-bottom: 20px; border-bottom: 2px solid #eee; }
        .report-header h1 { margin: 0; }
        .doacao-card { border: 1px solid #ddd; border-radius: 8px; margin-bottom: 20px; page-break-inside: avoid; }
        .doacao-card-header { background-color: #f8f9fa; padding: 10px 15px; border-bottom: 1px solid #ddd; font-weight: bold; }
        .doacao-card-body { padding: 15px; }
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none; }
            .doacao-card { border: 1px solid #ccc; }
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="report-header w-100">
                <h1>Relatório de Doações</h1>
                <p class="lead">Mês de Referência: {{ $mes }} de {{ $ano }}</p>
            </div>
            <button onclick="window.print()" class="btn btn-primary no-print">Imprimir Relatório</button>
        </div>

        @forelse ($doacoes as $doacao)
            <div class="doacao-card">
                <div class="doacao-card-header">
                    Doação #{{ $doacao->id }} - Data: {{ \Carbon\Carbon::parse($doacao->data_doacao)->format('d/m/Y H:i') }}
                </div>
                <div class="doacao-card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Beneficiário</h6>
                            <p class="mb-0"><strong>Nome:</strong> {{ $doacao->beneficiario?->nome ?? 'N/A' }}</p>
                            <p class="mb-0"><strong>CPF:</strong> {{ $doacao->beneficiario?->formatted_cpf ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Funcionário Responsável</h6>
                            <p class="mb-0"><strong>Nome:</strong> {{ $doacao->funcionario?->nome ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <hr>
                    <h6>Itens Doados</h6>
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Categoria</th>
                                <th>Quantidade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($doacao->itens as $item)
                                <tr>
                                    <td>{{ $item->nome_item }}</td>
                                    <td>{{ $item->categoria_principal }}</td>
                                    <td>{{ $item->pivot->quantidade_doada }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="alert alert-info text-center">
                Nenhuma doação encontrada para o período selecionado.
            </div>
        @endforelse
    </div>
</body>
</html>
