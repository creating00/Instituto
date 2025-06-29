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
        Schema::create('curso', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 250);
            $table->float('precio');
            $table->integer('duracion');
            $table->integer('estado');
            $table->string('tipo', 100);

            $table->unsignedBigInteger('idsede')->nullable();
            $table->integer('dias')->nullable();
            $table->double('inscripcion')->nullable();
            $table->unsignedBigInteger('idprofesor')->nullable();
            $table->double('monto')->nullable();
            $table->date('fechaComienzo')->nullable();
            $table->date('fechaTermino')->nullable();
            $table->float('mora')->nullable();
            $table->integer('diasRecordatorio')->nullable();
            $table->time('horarioDesde')->nullable();
            $table->time('horarioHasta')->nullable();

            $table->timestamps();

            // Clave foránea a sedes
            $table->foreign('idsede')
                ->references('id')->on('sedes')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curso');
    }
};
