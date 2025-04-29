import 'enums/tipoRelatorio_enum.dart';

class Relatorio {
  final int id;
  final DateTime dataRelatorio;
  final String formato;
  final TiporelatorioEnum tipoRelatorio;
  final String descricao;

  Relatorio({
    required this.id,
    required this.dataRelatorio,
    required this.formato,
    required this.tipoRelatorio,
    required this.descricao,
  });
}
