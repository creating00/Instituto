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
        Schema::create('cuotas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idinscripcion');
            $table->date('fecha')->nullable();
            $table->integer('cuota');
            $table->string('mes', 20);
            $table->integer('anio');
            $table->float('importe');
            $table->float('interes');
            $table->float('total');
            $table->string('condicion', 100);
            $table->string('mediodepago', 250);
            $table->unsignedBigInteger('idusuario');
            $table->unsignedBigInteger('idexamen');
            $table->boolean('isDetalle')->nullable();
            $table->string('detalle', 100)->nullable();
            $table->boolean('tieneMora')->default(false);
            $table->string('nroFactura', 30)->nullable();

            $table->timestamps();

            $table->index('idinscripcion');
            $table->index('idusuario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuotas');
    }
};
