import 'dart:io';
import 'dart:convert';
import 'package:shelf/shelf.dart' as shelf;
import 'package:shelf/shelf_io.dart' as io;
import 'package:postgres/postgres.dart';
      
void main() async {
  //mandei no zap
  final pgHost = '';
  final pgPort = 0;
  final pgDatabase = '';
  final pgUser = '';
  final pgPassword = '';
        
  final connection = PostgreSQLConnection(
    pgHost,
    pgPort,
    pgDatabase,
    username: pgUser,
    password: pgPassword,
    useSSL: true,
  );
        
  try {
    await connection.open();
    print('Conexão com o banco de dados estabelecida com sucesso.');
  } catch (e) {
    print('Erro ao conectar com o banco de dados: $e');
    exit(1);
  }

  shelf.Middleware corsMiddleware = shelf.createMiddleware(
    responseHandler: (response) {
      return response.change(headers: {
        'Access-Control-Allow-Origin': '*',
        'Access-Control-Allow-Methods': 'GET, POST, PUT, DELETE, OPTIONS',
        'Access-Control-Allow-Headers': 'Origin, Content-Type, X-Auth-Token',
        'Access-Control-Allow-Credentials': 'true',
      });
    },
  );
  
  //ROTAS
  final handler = const shelf.Pipeline()
    .addMiddleware(shelf.logRequests())
    .addMiddleware(corsMiddleware)
    .addHandler((request) async {
      if (request.method == 'OPTIONS') {
        return shelf.Response.ok('', headers: {
          'Access-Control-Allow-Origin': '*',
          'Access-Control-Allow-Methods': 'GET, POST, PUT, DELETE, OPTIONS',
          'Access-Control-Allow-Headers': 'Origin, Content-Type, X-Auth-Token',
          'Access-Control-Allow-Credentials': 'true',
        });
      }
      final path = request.url.path;
      
      print('$path');

      if (path == '') {
        return shelf.Response.ok('Hello, World!\n');
      }
            
      if (path.startsWith('echo/')) {
        final message = path.substring('echo/'.length);
        return shelf.Response.ok('$message\n');
      }

      //ROTAS ESTADOS      
      if (path == 'api/estados' && request.method == 'GET') {
        try {
          final results = await connection.query(
            'SELECT id, nome FROM estado ORDER BY nome'
          );
          
          print('Estados encontrados: ${results.length}');
          for(var row in results) {
            print('Estado: ${row[0]}, Nome: ${row[1]}');
          }
                    
          final estados = results.map((row) => {
            'id': row[0],
            'nome': row[1]
          }).toList();
                    
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
            
      final estadoIdRegExp = RegExp(r'^api/estados/(\d+)$');
      final match = estadoIdRegExp.firstMatch(path);
            
      if (match != null && request.method == 'GET') {
        final id = int.parse(match.group(1)!);
              
        try {
          final results = await connection.query(
            'SELECT id, nome FROM estado WHERE id = @id',
            substitutionValues: {'id': id}
          );
                
          if (results.isEmpty) {
            return shelf.Response.notFound(
              json.encode({'error': 'Estado não encontrado'}),
              headers: {'content-type': 'application/json'},
            );
          }
                
          final row = results.first;
          final estado = {
            'id': row[0],
            'nome': row[1]
          };
                
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
            
      return shelf.Response.notFound(
        json.encode({'error': 'Rota não encontrada'}),
        headers: {'content-type': 'application/json'},
      );
    });
  
  final port = int.parse(Platform.environment['PORT'] ?? '8080');
  final server = await io.serve(handler, 'localhost', port);
  
  print('Servidor rodando em http://${server.address.host}:${server.port}');
}