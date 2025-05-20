enum TipoItem {
  alimento,
  vestuario,
  higiene,
  limpeza,
  medicamento,
  movel,
  eletrodomestico,
  outro,
}

class ItemDoacao {
  final int id;
  final String nome;
  final String descricao;
  final int quantidade;
  final TipoItem tipo;
  
  ItemDoacao({
    required this.id,
    required this.nome,
    required this.descricao,
    required this.quantidade,
    required this.tipo,
  });
}