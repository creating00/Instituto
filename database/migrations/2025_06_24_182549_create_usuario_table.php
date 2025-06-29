<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuarioTable extends Migration
{
    public function up()
    {
        Schema::create('usuario', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('correo', 100)->unique();
            $table->string('usuario', 20)->unique();
            $table->string('clave'); // recomendado varchar(255) para hash bcrypt
            $table->integer('estado')->default(1);
            $table->integer('idsede');
            $table->integer('idrol');
            $table->timestamps(); // created_at y updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('usuario');
    }
}
