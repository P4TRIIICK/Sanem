class Produto {
  final int id;
  String nome;
  bool qualidade;

  Produto({
    required this.id,
    required this.nome,
    this.qualidade = true,
  });
}
