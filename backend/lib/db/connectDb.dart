import 'package:postgres/postgres.dart';

class DatabaseConnection {
  static PostgreSQLConnection? _connection;

  static Future<PostgreSQLConnection> get connection async {
    if (_connection != null && _connection!.isClosed == false) {
      return _connection!;
    }

    //mandei no zap
    final pgHost = 'ballast.proxy.rlwy.net';
    final pgPort = 43147;
    final pgDatabase = 'railway';
    final pgUser = 'postgres';
    final pgPassword = 'CKhmHlhYBhiAacBxyvxicxkxhRkCoPXC';

    _connection = PostgreSQLConnection(
      pgHost,
      pgPort,
      pgDatabase,
      username: pgUser,
      password: pgPassword,
      useSSL: true,
    );

    try {
      await _connection!.open();
      print('Conexão com o banco de dados estabelecida com sucesso.');
    } catch (e) {
      print('Erro ao conectar com o banco de dados: $e');
      rethrow;
    }

    return _connection!;
  }
}
