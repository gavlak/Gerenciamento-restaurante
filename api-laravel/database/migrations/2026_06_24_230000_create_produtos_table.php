<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 200);
            $table->decimal('quantidade', 10, 3)->default(0);
            $table->decimal('quantidade_minima', 10, 3)->default(1);
            $table->string('unidade', 10)->default('UN');
            $table->decimal('valor', 10, 2)->default(0);
            $table->date('data_compra')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};
