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
        Schema::create('inscripcion', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('idusuario');
            $table->unsignedInteger('idalumno');
            $table->unsignedInteger('idcurso');
            $table->unsignedInteger('idsala');
            $table->unsignedInteger('idprofesor')->nullable();

            $table->timestamp('fecha')->useCurrent()->useCurrentOnUpdate();
            $table->date('fechacomienzo');
            $table->float('importe');
            $table->string('mediodepago', 100);
            $table->unsignedInteger('idsede');
            $table->integer('estado');
            $table->string('mes', 20);
            $table->integer('anio');
            $table->date('fechaTermino');
            $table->boolean('isDetalle')->nullable();
            $table->text('detalle')->nullable();
            $table->string('nroFactura', 30)->nullable();
            $table->boolean('activo')->default(true);

            $table->timestamps();

            // $table->foreign('idalumno')->references('idalumno')->on('alumno')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscripcion');
    }
};