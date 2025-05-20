import 'endereco.dart';

enum Genero { MASCULINO, FEMININO, OUTRO }
enum TipoBenef { BENEFICIARIO, DOADOR, BENEFICIARIO_DOADOR }

class Pessoa {
  final int id;
  String nome;
  String cpf;
  String? rg;
  Genero genero;
  TipoBenef tipoBeneficiario;
  DateTime? dataNascimento;
  String? email;
  Endereco endereco;
  List<String> telefones;

  Pessoa({
    required this.id,
    required this.nome,
    required this.cpf,
    this.rg,
    required this.genero,
    required this.tipoBeneficiario,
    this.dataNascimento,
    this.email,
    required this.endereco,
    required this.telefones,
  });
}
