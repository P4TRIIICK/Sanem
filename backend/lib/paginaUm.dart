import 'package:flutter/material.dart';
import 'api/crudEstado.dart';
import 'models/estado.dart';

class PaginaUm extends StatefulWidget {
  const PaginaUm({super.key});

  @override
  State<PaginaUm> createState() => _PaginaUmState();
}

class _PaginaUmState extends State<PaginaUm> {
  final EstadosApi _estadosApi = EstadosApi();
  List<Estado> _estados = [];
  bool _isLoading = false;
  String? _errorMessage;

  @override
  void initState() {
    super.initState();
    _carregarEstados();
  }

  Future<void> _carregarEstados() async {
    setState(() {
      _isLoading = true;
      _errorMessage = null;
    });

    try {
      final estados = await _estadosApi.getEstados();
      setState(() {
        _estados = estados;
        _isLoading = false;
      });
    } catch (e) {
      setState(() {
        _errorMessage = 'Erro ao carregar estados: $e';
        _isLoading = false;
      });
    }
  }

  Future<void> _exibirDialogoAdicionarEstado() async {
    final TextEditingController nomeController = TextEditingController();
    String? erro;

    await showDialog(
      context: context,
      builder: (context) {
        return StatefulBuilder(
          builder: (context, setState) {
            return AlertDialog(
              title: const Text('Adicionar Estado'),
              content: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  TextField(
                    controller: nomeController,
                    decoration: InputDecoration(
                      labelText: 'Nome do Estado',
                      errorText: erro,
                    ),
                    autofocus: true,
                  ),
                ],
              ),
              actions: [
                TextButton(
                  onPressed: () => Navigator.pop(context),
                  child: const Text('Cancelar'),
                ),
                TextButton(
                  onPressed: () async {
                    // Validação local
                    if (nomeController.text.trim().isEmpty) {
                      setState(() {
                        erro = 'Nome do estado é obrigatório';
                      });
                      return;
                    }

                    try {
                      await _estadosApi.adicionarEstado(nomeController.text.trim());
                      if (!context.mounted) return;
                      Navigator.pop(context);
                      _carregarEstados(); // Recarrega a lista após adicionar
                      _mostrarSnackBar('Estado adicionado com sucesso!');
                    } catch (e) {
                      if (!context.mounted) return;
                      Navigator.pop(context);
                      _mostrarSnackBar('Erro ao adicionar estado: $e');
                    }
                  },
                  child: const Text('Adicionar'),
                ),
              ],
            );
          },
        );
      },
    );
  }

  Future<void> _exibirDialogoEditarEstado(Estado estado) async {
    final TextEditingController nomeController = TextEditingController(text: estado.nome);
    String? erro;

    await showDialog(
      context: context,
      builder: (context) {
        return StatefulBuilder(
          builder: (context, setState) {
            return AlertDialog(
              title: const Text('Editar Estado'),
              content: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  TextField(
                    controller: nomeController,
                    decoration: InputDecoration(
                      labelText: 'Nome do Estado',
                      errorText: erro,
                    ),
                    autofocus: true,
                  ),
                ],
              ),
              actions: [
                TextButton(
                  onPressed: () => Navigator.pop(context),
                  child: const Text('Cancelar'),
                ),
                TextButton(
                  onPressed: () async {
                    // Validação local
                    if (nomeController.text.trim().isEmpty) {
                      setState(() {
                        erro = 'Nome do estado é obrigatório';
                      });
                      return;
                    }

                    try {
                      await _estadosApi.atualizaEstado(estado.id, nomeController.text.trim());
                      if (!context.mounted) return;
                      Navigator.pop(context);
                      _carregarEstados(); // Recarrega a lista após editar
                      _mostrarSnackBar('Estado atualizado com sucesso!');
                    } catch (e) {
                      if (!context.mounted) return;
                      Navigator.pop(context);
                      _mostrarSnackBar('Erro ao atualizar estado: $e');
                    }
                  },
                  child: const Text('Salvar'),
                ),
              ],
            );
          },
        );
      },
    );
  }

  Future<void> _exibirDialogoConfirmacaoExclusao(Estado estado) async {
    return showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: const Text('Confirmar exclusão'),
          content: Text('Deseja realmente excluir o estado "${estado.nome}"?'),
          actions: <Widget>[
            TextButton(
              onPressed: () => Navigator.of(context).pop(),
              child: const Text('Cancelar'),
            ),
            TextButton(
              style: TextButton.styleFrom(foregroundColor: Colors.red),
              onPressed: () async {
                Navigator.of(context).pop();
                try {
                  await _estadosApi.excluirEstado(estado.id);
                  _carregarEstados();
                  _mostrarSnackBar('Estado excluído com sucesso!');
                } catch (e) {
                  _mostrarSnackBar('Erro ao excluir estado: $e');
                }
              },
              child: const Text('Excluir'),
            ),
          ],
        );
      },
    );
  }

  void _mostrarSnackBar(String mensagem) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text(mensagem)),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Estados'),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: _carregarEstados,
          ),
        ],
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _errorMessage != null
              ? Center(child: Text(_errorMessage!, style: const TextStyle(color: Colors.red)))
              : _estados.isEmpty
                  ? const Center(child: Text('Nenhum estado cadastrado'))
                  : ListView.builder(
                      itemCount: _estados.length,
                      itemBuilder: (context, index) {
                        final estado = _estados[index];
                        return Card(
                          margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                          child: ListTile(
                            title: Text(estado.nome),
                            subtitle: Text('ID: ${estado.id}'),
                            trailing: Row(
                              mainAxisSize: MainAxisSize.min,
                              children: [
                                // Botão de editar (verde)
                                IconButton(
                                  icon: const Icon(Icons.edit),
                                  color: Colors.green,
                                  onPressed: () => _exibirDialogoEditarEstado(estado),
                                  tooltip: 'Alterar estado',
                                ),
                                // Botão de excluir (vermelho)
                                IconButton(
                                  icon: const Icon(Icons.delete),
                                  color: Colors.red,
                                  onPressed: () => _exibirDialogoConfirmacaoExclusao(estado),
                                  tooltip: 'Excluir estado',
                                ),
                              ],
                            ),
                          ),
                        );
                      },
                    ),
      floatingActionButton: FloatingActionButton(
        onPressed: _exibirDialogoAdicionarEstado,
        tooltip: 'Adicionar Estado',
        child: const Icon(Icons.add),
      ),
    );
  }
}