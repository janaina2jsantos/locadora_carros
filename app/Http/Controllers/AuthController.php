<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // tenta fazer a autenticação (email e senha)
        $dados = $request->all(['email', 'password']);
        // o auth() pode ser web ou api (ver config/auth -- guards)
        $token = auth('api')->attempt($dados); 

        if ($token) {
            // retorna um token JWT
            return response()->json(['token' => $token], 200);
        }
        else {
            // status 403 = forbidden (não logado, proibido)
            // status 401 = unauthorized (não autorizado, mesmo estando logado)
            return response()->json(['error' => 'Usuário ou senha inválida!'], 403);
        }
    }

    public function logout()
    {
        return "logout";
    }

    public function refresh()
    {
        return "refresh";
    }

    public function profile()
    {
        return "profile";
    }
}
