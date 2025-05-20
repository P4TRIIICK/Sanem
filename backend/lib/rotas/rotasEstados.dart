import 'dart:convert';
import 'package:shelf/shelf.dart' as shelf;
import '../db/connectDb.dart';

class EstadoRoutes {
  static Future<shelf.Response> handleRequest(shelf.Request request) async {
    final path = request.url.path;
    
    //GetAll
    if (path == 'api/estados' && request.method == 'GET') {
      return await getEstados();
    }
    
    //Cria
    if (path == 'api/estados' && request.method == 'POST') {
      return await createEstado(request);
    }
    
    //Update
    if (path.startsWith('api/estados/') && request.method == 'PUT') {
      return await updateEstado(request);
    }
    
    //estado by id
    final estadoIdRegExp = RegExp(r'^api/estados/(\d+)$');
    final match = estadoIdRegExp.firstMatch(path);
    
    if (match != null && request.method == 'GET') {
      final id = int.parse(match.group(1)!);
      return await getEstadoById(id);
    }
    
    //Delete
    if (match != null && request.method == 'DELETE') {
      final id = int.parse(match.group(1)!);
      return await deleteEstado(id);
    }
    
    return shelf.Response.notFound(
      json.encode({'error': 'Rota de estado não encontrada'}),
      headers: {'content-type': 'application/json'},
    );
  }
  
//####################################################################
  //get all
  static Future<shelf.Response> getEstados() async {
    try {
      final connection = await DatabaseConnection.connection;
      final results = await connection.query(
        'SELECT id, nome FROM estado ORDER BY nome',
      );

      print('Estados encontrados: ${results.length}');
      final estados = results.map((row) => {'id': row[0], 'nome': row[1]}).toList();

      return shelf.Response.ok(
        json.encode(estados),
        headers: {'content-type': 'application/json'},
      );
    } catch (e) {
      print('Erro ao buscar estados: $e');
      return shelf.Response.internalServerError(
        body: json.encode({'error': 'Erro ao buscar estados: $e'}),
        headers: {'content-type': 'application/json'},
      );
    }
  }
  
  //Create
  static Future<shelf.Response> createEstado(shelf.Request request) async {
    try {
      final connection = await DatabaseConnection.connection;
      final requestBody = await request.readAsString();
      final Map<String, dynamic> data = json.decode(requestBody);

      if (!data.containsKey('nome') || data['nome'].toString().trim().isEmpty) {
        return shelf.Response.badRequest(
          body: json.encode({'error': 'Nome do estado é obrigatório'}),
          headers: {'content-type': 'application/json'},
        );
      }
      final nome = data['nome'].toString();

      final checkResults = await connection.query(
        'SELECT id FROM estado WHERE nome = @nome',
        substitutionValues: {'nome': nome},
      );

      if (checkResults.isNotEmpty) {
        return shelf.Response(
          409,
          body: json.encode({'error': 'Estado já cadastrado'}),
          headers: {'content-type': 'application/json'},
        );
      }

      final results = await connection.query(
        'INSERT INTO estado (nome) VALUES (@nome) RETURNING id, nome',
        substitutionValues: {'nome': nome},
      );

      final row = results.first;
      final estado = {'id': row[0], 'nome': row[1]};

      print('Estado criado: $estado');

      return shelf.Response(
        201,
        body: json.encode(estado),
        headers: {'content-type': 'application/json'},
      );
    } catch (e) {
      print('Erro ao criar estado: $e');
      return shelf.Response.internalServerError(
        body: json.encode({'error': 'Erro ao criar estado: $e'}),
        headers: {'content-type': 'application/json'},
      );
    }
  }
  
  //Update
  static Future<shelf.Response> updateEstado(shelf.Request request) async {
    try {
      final connection = await DatabaseConnection.connection;
      final path = request.url.path;
      final id = int.tryParse(path.split('/').last);
      
      if (id == null) {
        return shelf.Response.badRequest(
          body: json.encode({'error': 'ID inválido'}),
          headers: {'content-type': 'application/json'},
        );
      }

      final requestBody = await request.readAsString();
      final Map<String, dynamic> data = json.decode(requestBody);

      if (!data.containsKey('nome') || data['nome'].toString().trim().isEmpty) {
        return shelf.Response.badRequest(
          body: json.encode({'error': 'Nome do estado é obrigatório'}),
          headers: {'content-type': 'application/json'},
        );
      }

      final String novoNome = data['nome'].toString().trim();

      final checkResults = await connection.query(
        'SELECT id FROM estado WHERE id = @id',
        substitutionValues: {'id': id},
      );

      if (checkResults.isEmpty) {
        return shelf.Response.notFound(
          json.encode({'error': 'Estado não encontrado'}),
          headers: {'content-type': 'application/json'},
        );
      }

      final results = await connection.query(
        'UPDATE estado SET nome = @nome WHERE id = @id RETURNING id, nome',
        substitutionValues: {'id': id, 'nome': novoNome},
      );

      final row = results.first;
      final estadoAtualizado = {'id': row[0], 'nome': row[1]};

      print('Estado atualizado: $estadoAtualizado');

      return shelf.Response.ok(
        json.encode(estadoAtualizado),
        headers: {'content-type': 'application/json'},
      );
    } catch (e) {
      print('Erro ao atualizar estado: $e');
      return shelf.Response.internalServerError(
        body: json.encode({'error': 'Erro ao atualizar estado: $e'}),
        headers: {'content-type': 'application/json'},
      );
    }
  }
  
  //Get by id
  static Future<shelf.Response> getEstadoById(int id) async {
    try {
      final connection = await DatabaseConnection.connection;
      final results = await connection.query(
        'SELECT id, nome FROM estado WHERE id = @id',
        substitutionValues: {'id': id},
      );

      if (results.isEmpty) {
        return shelf.Response.notFound(
          json.encode({'error': 'Estado não encontrado'}),
          headers: {'content-type': 'application/json'},
        );
      }

      final row = results.first;
      final estado = {'id': row[0], 'nome': row[1]};

      return shelf.Response.ok(
        json.encode(estado),
        headers: {'content-type': 'application/json'},
      );
    } catch (e) {
      print('Erro ao buscar estado por ID: $e');
      return shelf.Response.internalServerError(
        body: json.encode({'error': 'Erro ao buscar estado: $e'}),
        headers: {'content-type': 'application/json'},
      );
    }
  }
  
  //Delete
  static Future<shelf.Response> deleteEstado(int id) async {
    try {
      final connection = await DatabaseConnection.connection;
      
      final checkResults = await connection.query(
        'SELECT id FROM estado WHERE id = @id',
        substitutionValues: {'id': id},
      );

      if (checkResults.isEmpty) {
        return shelf.Response.notFound(
          json.encode({'error': 'Estado não encontrado'}),
          headers: {'content-type': 'application/json'},
        );
      }

      await connection.query(
        'DELETE FROM estado WHERE id = @id',
        substitutionValues: {'id': id},
      );

      return shelf.Response.ok(
        json.encode({'message': 'Estado excluído com sucesso'}),
        headers: {'content-type': 'application/json'},
      );
    } catch (e) {
      print('Erro ao excluir estado: $e');
      return shelf.Response.internalServerError(
        body: json.encode({'error': 'Erro ao excluir estado: $e'}),
        headers: {'content-type': 'application/json'},
      );
    }
  }
}
