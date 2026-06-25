<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cardapio;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CardapioController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $cardapios = Cardapio::with('produtos')->orderBy('nome')->get();

        return $this->sucesso($cardapios, 'Lista de cardapios.');
    }

    public function store(Request $request): JsonResponse
    {
        $dados = $request->validate([
            'nome'      => 'required|string|min:2|max:150',
            'dia'       => 'required|in:Segunda,Terca,Quarta,Quinta,Sexta,Sabado,Domingo',
            'detalhes'  => 'required|string|min:2|max:2000',
            'insumos'   => 'sometimes|array',
            'insumos.*' => 'integer|exists:produtos,id',
        ]);

        $cardapio = Cardapio::create([
            'nome'     => $dados['nome'],
            'dia'      => $dados['dia'],
            'detalhes' => $dados['detalhes'],
        ]);

        $cardapio->produtos()->sync($dados['insumos'] ?? []);

        return $this->sucesso($cardapio->load('produtos'), 'Cardapio cadastrado com sucesso.', 201);
    }

    public function show(string $id): JsonResponse
    {
        $cardapio = Cardapio::with('produtos')->find($id);

        if (! $cardapio) {
            return $this->erro('Cardapio nao encontrado.', 404);
        }

        return $this->sucesso($cardapio, 'Detalhes do cardapio.');
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $cardapio = Cardapio::find($id);

        if (! $cardapio) {
            return $this->erro('Cardapio nao encontrado.', 404);
        }

        $dados = $request->validate([
            'nome'      => 'required|string|min:2|max:150',
            'dia'       => 'required|in:Segunda,Terca,Quarta,Quinta,Sexta,Sabado,Domingo',
            'detalhes'  => 'required|string|min:2|max:2000',
            'insumos'   => 'sometimes|array',
            'insumos.*' => 'integer|exists:produtos,id',
        ]);

        $cardapio->update([
            'nome'     => $dados['nome'],
            'dia'      => $dados['dia'],
            'detalhes' => $dados['detalhes'],
        ]);

        $cardapio->produtos()->sync($dados['insumos'] ?? []);

        return $this->sucesso($cardapio->load('produtos'), 'Cardapio atualizado com sucesso.');
    }

    public function destroy(string $id): JsonResponse
    {
        $cardapio = Cardapio::find($id);

        if (! $cardapio) {
            return $this->erro('Cardapio nao encontrado.', 404);
        }

        $cardapio->delete();

        return $this->sucesso(null, 'Cardapio removido com sucesso.');
    }
}
