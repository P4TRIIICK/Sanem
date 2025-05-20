import 'item_doacao.dart';
import 'beneficiario.dart';

enum StatusDoacao { APta, Negada }

class Doacao {
  final int id;
  final DateTime instante;
  final StatusDoacao statusDoacao;
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
