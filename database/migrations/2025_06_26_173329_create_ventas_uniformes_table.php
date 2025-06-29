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
        Schema::create('ventas_uniformes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_uniforme');
            $table->unsignedBigInteger('id_alumno');
            $table->integer('cantidad');
            $table->decimal('total', 10, 2);
            $table->dateTime('fecha_venta');
            $table->string('medio_pago', 50);
            $table->string('numero_factura', 50)->nullable();
            $table->boolean('vino_de_inscripcion')->default(false);
            $table->decimal('precio_unitario', 10, 2);
            $table->unsignedBigInteger('id_usuario');

            // Índices
            $table->index('id_uniforme');
            $table->index('id_alumno');
            $table->index('id_usuario');

            // Foreign keys
            $table->foreign('id_usuario')
                ->references('id')->on('usuario')
                ->onDelete('cascade');

            $table->foreign('id_uniforme')
                ->references('id')->on('uniformes')
                ->onDelete('cascade');

            $table->foreign('id_alumno')
                ->references('id')->on('alumno')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas_uniformes');
    }
};
