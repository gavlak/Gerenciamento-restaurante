<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cardapio_produtos', function (Blueprint $table) {
            $table->foreignId('cardapio_id')->constrained('cardapios')->cascadeOnDelete();
            $table->foreignId('produto_id')->constrained('produtos')->cascadeOnDelete();
            $table->primary(['cardapio_id', 'produto_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cardapio_produtos');
    }
};
