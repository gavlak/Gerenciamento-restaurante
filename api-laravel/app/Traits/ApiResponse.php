<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function sucesso(mixed $dados = null, string $mensagem = 'OK', int $codigo = 200): JsonResponse
    {
        return response()->json([
            'status'  => 'success',
            'message' => $mensagem,
            'data'    => $dados,
        ], $codigo);
    }

    protected function erro(string $mensagem = 'Erro', int $codigo = 400, mixed $erros = null): JsonResponse
    {
        return response()->json([
            'status'  => 'error',
            'message' => $mensagem,
            'errors'  => $erros,
        ], $codigo);
    }
}
