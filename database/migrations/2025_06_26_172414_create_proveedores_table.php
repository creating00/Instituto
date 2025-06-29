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
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 250);
            $table->string('direccion', 300);
            $table->string('telefono', 50);
            $table->string('correo', 200);
            $table->unsignedBigInteger('idsede');
            $table->unsignedBigInteger('idusuario');
            $table->timestamps();

            $table->index('idsede');
            $table->index('idusuario');

            $table->foreign('idsede')->references('id')->on('sedes')->onDelete('cascade');
            $table->foreign('idusuario')->references('id')->on('usuario')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};
