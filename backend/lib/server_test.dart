import 'dart:io';
import 'dart:convert';
import 'package:shelf/shelf.dart' as shelf;
import 'package:shelf/shelf_io.dart' as io;
import '../rotas/rotasEstados.dart';
import '../db/connectDb.dart';

void main() async {
  try {
    // Initialize database connection
    await DatabaseConnection.connection;
    
    // CORS middleware
    shelf.Middleware corsMiddleware = shelf.createMiddleware(
      responseHandler: (response) {
        return response.change(
          headers: {
            'Access-Control-Allow-Origin': '*',
            'Access-Control-Allow-Methods': 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers': 'Origin, Content-Type, X-Auth-Token',
            'Access-Control-Allow-Credentials': 'true',
          },
        );
      },
    );

    // ROUTES
    final handler = const shelf.Pipeline()
        .addMiddleware(shelf.logRequests())
        .addMiddleware(corsMiddleware)
        .addHandler((request) async {
          if (request.method == 'OPTIONS') {
            return shelf.Response.ok(
              '',
              headers: {
                'Access-Control-Allow-Origin': '*',
                'Access-Control-Allow-Methods': 'GET, POST, PUT, DELETE, OPTIONS',
                'Access-Control-Allow-Headers':
                    'Origin, Content-Type, X-Auth-Token',
                'Access-Control-Allow-Credentials': 'true',
              },
            );
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

          // Estado routes
          if (path == 'api/estados' || path.startsWith('api/estados/')) {
            return await EstadoRoutes.handleRequest(request);
          }
          
          // You can add other route handlers here for different tables
          // Example:
          // if (path == 'api/cidades' || path.startsWith('api/cidades/')) {
          //   return await CidadeRoutes.handleRequest(request);
          // }

          return shelf.Response.notFound(
            json.encode({'error': 'Rota não encontrada'}),
            headers: {'content-type': 'application/json'},
          );
        });

    final port = int.parse(Platform.environment['PORT'] ?? '8080');
    final server = await io.serve(handler, 'localhost', port);

    print('Servidor rodando em http://${server.address.host}:${server.port}');
  } catch (e) {
    print('Erro ao iniciar o servidor: $e');
    exit(1);
  }
}
