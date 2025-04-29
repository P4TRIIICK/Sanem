import 'item_doacao.dart';
import 'beneficiario.dart';

class Doacao {
  final int id;
  final DateTime instante;
  final bool statusDoacao; //Criar enum para substituir o booleano
  final List<ItemDoacao> itens;
  final Beneficiario beneficiario;

  Doacao({
    required this.id,
    required this.instante,
    required this.statusDoacao,
    required this.itens,
    required this.beneficiario,
  });
}
