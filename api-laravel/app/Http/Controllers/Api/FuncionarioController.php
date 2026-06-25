<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Funcionario;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FuncionarioController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $funcionarios = Funcionario::orderBy('nome')->get();

        return $this->sucesso($funcionarios, 'Lista de funcionarios.');
    }

    public function store(Request $request): JsonResponse
    {
        $dados = $request->validate([
            'nome'     => 'required|string|min:2|max:150',
            'cargo'    => 'required|string|min:2|max:100',
            'telefone' => 'nullable|string|max:20',
        ]);

        $funcionario = Funcionario::create($dados);

        return $this->sucesso($funcionario, 'Funcionario cadastrado com sucesso.', 201);
    }

    public function show(string $id): JsonResponse
    {
        $funcionario = Funcionario::find($id);

        if (! $funcionario) {
            return $this->erro('Funcionario nao encontrado.', 404);
        }

        return $this->sucesso($funcionario, 'Detalhes do funcionario.');
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $funcionario = Funcionario::find($id);

        if (! $funcionario) {
            return $this->erro('Funcionario nao encontrado.', 404);
        }

        $dados = $request->validate([
            'nome'     => 'required|string|min:2|max:150',
            'cargo'    => 'required|string|min:2|max:100',
            'telefone' => 'nullable|string|max:20',
        ]);

        $funcionario->update($dados);

        return $this->sucesso($funcionario, 'Funcionario atualizado com sucesso.');
    }

    public function destroy(string $id): JsonResponse
    {
        $funcionario = Funcionario::find($id);

        if (! $funcionario) {
            return $this->erro('Funcionario nao encontrado.', 404);
        }

        $funcionario->delete();

        return $this->sucesso(null, 'Funcionario removido com sucesso.');
    }
}
