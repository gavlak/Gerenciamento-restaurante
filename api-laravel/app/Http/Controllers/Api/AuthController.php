<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(Request $request): JsonResponse
    {
        $dados = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $usuario = User::where('email', $dados['email'])->first();

        if (! $usuario || ! Hash::check($dados['password'], $usuario->password_hash)) {
            return $this->erro('As credenciais informadas estao incorretas.', 401);
        }

        $token = $usuario->createToken('api-token')->plainTextToken;

        return $this->sucesso([
            'user'       => $usuario,
            'token'      => $token,
            'token_type' => 'Bearer',
        ], 'Login realizado com sucesso.');
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->sucesso(null, 'Logout realizado com sucesso.');
    }

    public function me(Request $request): JsonResponse
    {
        return $this->sucesso($request->user(), 'Usuario autenticado.');
    }
}
