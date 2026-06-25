<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cardapio extends Model
{
    protected $table = 'cardapios';

    protected $fillable = [
        'nome',
        'dia',
        'detalhes',
    ];

    public function produtos(): BelongsToMany
    {
        return $this->belongsToMany(Produto::class, 'cardapio_produtos');
    }
}
