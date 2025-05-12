import 'package:flutter/material.dart';

class HomePage extends StatelessWidget {
  const HomePage({Key? key}) : super(key: key);

  void _navegarPara(BuildContext context, String rota) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text('Navegando para: $rota')),
    );
    // Aqui você pode implementar a navegação para as telas específicas
    // Navigator.pushNamed(context, rota);
  }


  @override
  Widget build(BuildContext context) {
    final opcoes = [
      {'titulo': 'Cadastro e Triagem de Doações', 'icone': Icons.add_box, 'rota': '/triagem'},
      {'titulo': 'Estoque de Itens', 'icone': Icons.inventory, 'rota': '/estoque'},
      {'titulo': 'Cadastro de Beneficiários', 'icone': Icons.person_add, 'rota': '/beneficiarios'},
      {'titulo': 'Entrega de Itens', 'icone': Icons.local_shipping, 'rota': '/entrega'},
      {'titulo': 'Relatórios e Históricos', 'icone': Icons.bar_chart, 'rota': '/relatorios'},
    ];

    return Scaffold(
      appBar: AppBar(
        title: const Text('Sistema de Controle Interno'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: ListView.builder(
          itemCount: opcoes.length,
          itemBuilder: (context, index) {
            final opcao = opcoes[index];
            return Card(
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
              margin: const EdgeInsets.symmetric(vertical: 8),
              elevation: 4,
              child: ListTile(
                leading: Icon(opcao['icone'] as IconData),
                title: Text(opcao['titulo'] as String),
                trailing: const Icon(Icons.arrow_forward_ios),
                onTap: () => _navegarPara(context, opcao['rota'] as String),
              ),
            );
          },
        ),
      ),
    );
  }
}