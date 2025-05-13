class AuthService {
  static Future<bool> login(String email, String password) async {
    // Simula um tempo de espera como se fosse uma chamada de API
    await Future.delayed(const Duration(seconds: 1));

    // Por enquanto, só aceita se o email for 'admin' e senha '1234'
    if (email == 'admin' && password == '1234') {
      return true;
    } else {
      return false;
      
    }
  }
}
