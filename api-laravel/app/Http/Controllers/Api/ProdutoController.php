<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $produtos = Produto::orderBy('nome')->get();

        return $this->sucesso($produtos, 'Lista de produtos.');
    }

    public function store(Request $request): JsonResponse
    {
        $request->merge(['unidade' => strtoupper(trim((string) $request->input('unidade')))]);

        $dados = $request->validate([
            'nome'              => 'required|string|min:2|max:200',
            'quantidade'        => 'required|numeric|min:0',
            'quantidade_minima' => 'required|numeric|min:0',
            'unidade'           => 'required|in:UN,KG,G,L,ML,CX,PCT',
            'valor'             => 'required|numeric|min:0',
            'data_compra'       => 'required|date',
        ]);

        $produto = Produto::create($dados);

        return $this->sucesso($produto, 'Produto cadastrado com sucesso.', 201);
    }

    public function show(string $id): JsonResponse
    {
        $produto = Produto::find($id);

        if (! $produto) {
            return $this->erro('Produto nao encontrado.', 404);
        }

        return $this->sucesso($produto, 'Detalhes do produto.');
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $produto = Produto::find($id);

        if (! $produto) {
            return $this->erro('Produto nao encontrado.', 404);
        }

        $request->merge(['unidade' => strtoupper(trim((string) $request->input('unidade')))]);

        $dados = $request->validate([
            'nome'              => 'required|string|min:2|max:200',
            'quantidade'        => 'required|numeric|min:0',
            'quantidade_minima' => 'required|numeric|min:0',
            'unidade'           => 'required|in:UN,KG,G,L,ML,CX,PCT',
            'valor'             => 'required|numeric|min:0',
            'data_compra'       => 'required|date',
        ]);

        $produto->update($dados);

        return $this->sucesso($produto, 'Produto atualizado com sucesso.');
    }

    public function destroy(string $id): JsonResponse
    {
        $produto = Produto::find($id);

        if (! $produto) {
            return $this->erro('Produto nao encontrado.', 404);
        }

        $produto->delete();

        return $this->sucesso(null, 'Produto removido com sucesso.');
    }

    public function estoqueBaixo(): JsonResponse
    {
        $produtos = Produto::whereColumn('quantidade', '<', 'quantidade_minima')
            ->orderBy('nome')
            ->get();

        return $this->sucesso($produtos, 'Produtos com estoque baixo.');
    }
}
