<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('nombre');
            $table->enum('presentacion_tipo', ['unidad', 'peso'])->default('unidad');
            $table->string('presentacion_valor')->nullable();
            $table->string('imagen')->nullable();
            $table->decimal('valor_costo', 10, 2);
            $table->decimal('valor_venta', 10, 2);
            $table->string('marca')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
