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
        Schema::create('profesor', function (Blueprint $table) {
            $table->id();
            $table->integer('dni')->length(8);
            $table->string('nombre', 200);
            $table->string('apellido', 100);
            $table->string('direccion', 200);
            $table->string('celular', 50);
            $table->string('email', 200);
            $table->unsignedBigInteger('idsede')->nullable();
            $table->integer('estado');
            $table->timestamps();

            // Foreign key: sede
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
        Schema::dropIfExists('profesor');
    }
};
