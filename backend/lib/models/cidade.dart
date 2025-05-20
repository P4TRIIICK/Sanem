import 'estado.dart';

class Cidade {
  final int id;
  String nome;
  Estado estado;

  Cidade({
    required this.id,
    required this.nome,
    required this.estado,
  });
}
