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
        Schema::create('pagosprofesores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idprofesor');
            $table->unsignedBigInteger('idsede');
            $table->float('importe');
            $table->timestamp('fecha')->useCurrent()->useCurrentOnUpdate();
            $table->integer('mes');
            $table->integer('anio');
            $table->string('descripcion', 250);
            $table->timestamps();

            $table->foreign('idprofesor')->references('id')->on('profesor')->onDelete('cascade');
            $table->foreign('idsede')->references('id')->on('sedes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagosprofesores');
    }
};
