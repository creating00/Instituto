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
        Schema::create('detalle_venta', function (Blueprint $table) {
            $table->unsignedBigInteger('id_producto');
            $table->unsignedBigInteger('id_venta');
            $table->integer('cantidad');
            $table->decimal('precio', 10, 2);
            $table->timestamps();

            // Relaciones foráneas (opcional si las tablas existen)
            $table->foreign('id_producto')
                ->references('id')->on('producto')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('id_venta')
                ->references('id')->on('ventas')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_venta');
    }
};
