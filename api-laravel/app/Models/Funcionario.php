<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Funcionario extends Model
{
    protected $table = 'funcionarios';

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'cargo',
        'telefone',
    ];
}
