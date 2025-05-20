import 'funcionario.dart';

enum TipoRelatorio { DOACOES_RECEBIDAS, DOACOES_DISTRIBUIDAS, DOACOES_TODAS }

class Relatorio {
  final int id;
  DateTime dataRelatorio;
  String formato;
  TipoRelatorio tipoRelatorio;
  String? descricao;
  Funcionario funcionario;

  Relatorio({
    required this.id,
    required this.dataRelatorio,
    required this.formato,
    required this.tipoRelatorio,
    this.descricao,
    required this.funcionario,
  });
}
