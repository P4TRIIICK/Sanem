import 'pessoa.dart';

class Beneficiario extends Pessoa {
  bool ativo;
  String observacoes;

  Beneficiario({
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
    required this.ativo,
    this.observacoes = '',
  });
}
