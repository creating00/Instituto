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
        Schema::create('examen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idinscripcion');
            $table->float('interes');
            $table->float('total');
            $table->date('fecha');
            $table->string('mediodepago', 200);
            $table->string('sede', 100);
            $table->unsignedBigInteger('idusuario');
            $table->unsignedBigInteger('idcuota');
            $table->string('mes', 50);
            $table->integer('anio');
            $table->timestamps();

            // Relaciones (foreign keys)
            $table->foreign('idinscripcion')->references('id')->on('inscripcion')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('idusuario')->references('id')->on('usuario')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('idcuota')->references('id')->on('cuotas')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examen');
    }
};
