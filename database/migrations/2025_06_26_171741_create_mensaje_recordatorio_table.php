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
        Schema::create('mensaje_recordatorio', function (Blueprint $table) {
            $table->id();
            $table->text('contenidoMensaje')->nullable();
            $table->integer('estado')->nullable();
            $table->date('fechaUltimaActualizacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mensaje_recordatorio');
    }
};
