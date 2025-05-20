import 'pessoa.dart';

enum NivelAcesso { ADMINISTRADOR, CONSULTOR }

class Funcionario extends Pessoa {
  NivelAcesso nivelAcesso;
  double salario;
  DateTime dataContratacao;

  Funcionario({
    required super.id,
    required super.nome,
    required super.cpf,
    super.rg,
    required super.genero,
    required super.tipoBeneficiario,
    super.dataNascimento,
    super.email,
    required super.endereco,
    required super.telefones,
    required this.nivelAcesso,
    required this.salario,
    required this.dataContratacao,
  });
}
