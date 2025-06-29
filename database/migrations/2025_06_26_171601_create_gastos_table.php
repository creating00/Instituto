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
        Schema::create('gastos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idservicioproducto');
            $table->date('fecha');
            $table->float('total');
            $table->integer('mes');
            $table->integer('anio');
            $table->unsignedBigInteger('idsede');
            $table->unsignedBigInteger('idusuario');

            $table->timestamps();

            // Índices opcionales
            $table->index('idservicioproducto');
            $table->index('idsede');
            $table->index('idusuario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gastos');
    }
};
