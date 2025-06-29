<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('usuario', function (Blueprint $table) {
            // Cambiar la columna para que acepte NULL
            $table->unsignedBigInteger('idsede')->nullable()->change();

            // Definir la clave foránea con SET NULL al borrar
            $table->foreign('idsede')->references('id')->on('sedes')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('usuario', function (Blueprint $table) {
            $table->dropForeign(['idsede']);
        });
    }
};
