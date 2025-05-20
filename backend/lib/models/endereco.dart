import 'cidade.dart';

class Endereco {
  final int id;
  String logradouro;
  String numero;
  String? complemento;
  String? bairro;
  String? cep;
  Cidade cidade;

  Endereco({
    required this.id,
    required this.logradouro,
    required this.numero,
    this.complemento,
    this.bairro,
    this.cep,
    required this.cidade,
  });
}
