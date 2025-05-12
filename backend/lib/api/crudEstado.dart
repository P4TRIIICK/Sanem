import '../models/estado.dart';
import 'dart:convert';
import 'package:http/http.dart' as http;

import 'dart:io' show Platform;
import 'package:flutter/foundation.dart' show kIsWeb;

class EstadosApi {
  String get _baseUrl {
    if (kIsWeb) {
      return 'http://localhost:8080';
    }
    if (Platform.isAndroid) {
      return 'http://10.0.2.2:8080';
    }
    if (Platform.isIOS) {
      return 'http://127.0.0.1:8080';
    }
    return 'http://localhost:8080';
  }

  Future<List<Estado>> getEstados() async {
    try {
      final response = await http.get(Uri.parse('$_baseUrl/api/estados'));

      if (response.statusCode == 200) {
        List<dynamic> body = json.decode(response.body);

        List<Estado> estados = body
            .map((dynamic item) => Estado.fromMap(item))
            .toList();

        return estados;
      } else {
        throw Exception('Falha ao carregar estados. Código: ${response.statusCode}, Resposta: ${response.body}');
      }
    } catch (e) {
      throw Exception('Erro ao buscar estados: $e');
    }
  }

  Future<Estado> getEstadoById(int id) async {
    try {
      final response = await http.get(Uri.parse('$_baseUrl/api/estados/$id'));

      if (response.statusCode == 200) {
        Map<String, dynamic> body = json.decode(response.body);

        return Estado.fromMap(body);
      } else if (response.statusCode == 404) {
        throw Exception('Estado não encontrado');
      } else {
        throw Exception('Falha ao carregar estado. Código: ${response.statusCode}, Resposta: ${response.body}');
      }
    } catch (e) {
      throw Exception('Erro ao buscar estado: $e');
    }
  }
}