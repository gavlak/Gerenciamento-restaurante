<?php

namespace Database\Seeders;

use App\Models\Cardapio;
use App\Models\Funcionario;
use App\Models\Produto;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'          => 'Admin',
                'password_hash' => Hash::make('secret123'),
            ]
        );

        $arroz = Produto::create([
            'nome'              => 'Arroz',
            'quantidade'        => 10,
            'quantidade_minima' => 5,
            'unidade'           => 'KG',
            'valor'             => 25.90,
            'data_compra'       => '2026-06-01',
        ]);

        $feijao = Produto::create([
            'nome'              => 'Feijao',
            'quantidade'        => 2,
            'quantidade_minima' => 5,
            'unidade'           => 'KG',
            'valor'             => 8.50,
            'data_compra'       => '2026-06-10',
        ]);

        Produto::create([
            'nome'              => 'Oleo de Soja',
            'quantidade'        => 12,
            'quantidade_minima' => 6,
            'unidade'           => 'L',
            'valor'             => 7.20,
            'data_compra'       => '2026-06-15',
        ]);

        Funcionario::create([
            'nome'     => 'Maria Silva',
            'cargo'    => 'Cozinheira',
            'telefone' => '(45) 99999-0001',
        ]);

        Funcionario::create([
            'nome'     => 'Joao Souza',
            'cargo'    => 'Garcom',
            'telefone' => '(45) 99999-0002',
        ]);

        $cardapio = Cardapio::create([
            'nome'     => 'Almoco Executivo',
            'dia'      => 'Segunda',
            'detalhes' => 'Arroz, feijao e bife acebolado.',
        ]);
        $cardapio->produtos()->sync([$arroz->id, $feijao->id]);
    }
}
