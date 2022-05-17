<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // faz a autenticação do usuário (email e senha)
        $dados = $request->all(['email', 'password']);
        // o auth() pode ser web ou api (ver config/auth -- guards)
        $token = auth('api')->attempt($dados); 

        if($token) {
            // retorna um token JWT
            return response()->json(['token' => $token], 200);
        }
        else {
            // status 403 = forbidden (não logado, proibido)
            // status 401 = unauthorized (não autorizado, mesmo estando logado)
            return response()->json(['error' => 'Usuário ou senha inválida!'], 403);
        }
    }

    public function profile()
    {
        return response()->json(auth()->user(), 200);
    }

    public function refresh()
    {
        $token = auth('api')->refresh();
        return response()->json(['token' => $token], 200);
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['msg' => 'Logout realizado com sucesso.'], 200);
    }
}
