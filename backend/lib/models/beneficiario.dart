import 'pessoa.dart';

class Beneficiario extends Pessoa {
  final bool ativo;
  final String observacoes;

  Beneficiario({
    required super.id,
    required super.nome,
    required super.cpf,
    required super.rg,
    required super.genero,
    required super.tipoBeneficiario,
    required super.dataNascimento,
    required super.email,
    required super.endereco,
    required super.telefones,
    required this.ativo,
    this.observacoes = '',
  });
}