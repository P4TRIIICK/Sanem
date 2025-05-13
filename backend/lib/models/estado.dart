class Estado {
  final int id;
  final String nome;

  Estado({required this.id, required this.nome});

  factory Estado.fromMap(Map<String, dynamic> map) {
    return Estado(
      id: map['id'],
      nome: map['nome'],
    );
  }

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'nome': nome,
    };
  }

  @override
  String toString() {
    return 'Estado{id: $id, nome: $nome}';
  }
}