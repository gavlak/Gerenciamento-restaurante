<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Produto extends Model
{
    protected $table = 'produtos';

    protected $fillable = [
        'nome',
        'quantidade',
        'quantidade_minima',
        'unidade',
        'valor',
        'data_compra',
    ];

    public function cardapios(): BelongsToMany
    {
        return $this->belongsToMany(Cardapio::class, 'cardapio_produtos');
    }
}
