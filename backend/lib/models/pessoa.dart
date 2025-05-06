import 'endereco.dart';
import 'enums/genero_enum.dart';
import 'enums/tipo_beneficiario_enum.dart';

class Pessoa {
   int id;
  String nome;
   String cpf;
   String rg;
  GeneroEnum genero;
   TipoBeneficiarioEnum tipoBeneficiario;
   DateTime dataNascimento;
   String email;
   Endereco endereco;
  List<String> telefones;
  
    // Construtor da classe Pessoa
  
  Pessoa({
      required this.id,
      required this.nome,
      required this.cpf,
      required this.rg,
      required this.genero,
      required this.tipoBeneficiario,
      required this.dataNascimento,
      required this.email,
      required this.endereco,
      required this.telefones,
    });
    
 }  