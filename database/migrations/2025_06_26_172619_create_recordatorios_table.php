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
        Schema::create('recordatorios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_curso');
            $table->date('fecha_recordatorio');
            $table->enum('estado', ['pendiente', 'enviado', 'fallido'])->default('pendiente');
            $table->unsignedBigInteger('id_alumno');
            $table->timestamps();

            $table->foreign('id_curso')
                ->references('id')
                ->on('curso')
                ->onDelete('cascade');

            $table->foreign('id_alumno')
                ->references('id')
                ->on('alumno')
                ->onDelete('cascade');

            $table->index('id_curso');
            $table->index('id_alumno');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recordatorios');
    }
};
