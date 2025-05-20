import 'doacao.dart';
import 'produto.dart';

class ItemDoacao {
  final Doacao doacao;
  final Produto produto;
  int quantidade;

  ItemDoacao({
    required this.doacao,
    required this.produto,
    required this.quantidade,
  });
}
