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
        Schema::create('servicioproducto', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion', 200);
            $table->string('tipo', 100);
            $table->unsignedBigInteger('idproveedor');
            $table->integer('importe');
            $table->unsignedBigInteger('idsede');
            $table->unsignedBigInteger('idusuario');
            $table->timestamps();

            // $table->foreign('idproveedor')->references('idproveedor')->on('proveedores')->onDelete('cascade');
            // $table->foreign('idsede')->references('idsede')->on('sedes')->onDelete('cascade');
            // $table->foreign('idusuario')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicioproducto');
    }
};
