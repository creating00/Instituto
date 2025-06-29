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
        Schema::create('alumno', function (Blueprint $table) {
            $table->id();
            $table->integer('dni', false, true)->length(8);
            $table->string('nombre', 200);
            $table->string('apellido', 150);
            $table->string('direccion', 250);
            $table->string('celular', 50);
            $table->string('email', 250);
            $table->string('tutor', 200);
            $table->string('contacto', 50);
            $table->unsignedBigInteger('idsede')->nullable();
            $table->integer('estado');

            // Foreign key con SET NULL
            $table->foreign('idsede')
                ->references('id')->on('sedes')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumno');
    }
};
