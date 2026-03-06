<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('usuario')) {
            Schema::create('usuario', function (Blueprint $table) {
                $table->increments('idusu');
                $table->string('usuario', 50)->unique();
                $table->string('clave', 50);
                $table->string('cargo_usu', 100);
                $table->string('nombres', 100);
                $table->string('email', 100);
                $table->string('telefono', 15);
                $table->date('fechaingreso');
                $table->string('tipo');
                $table->string('estado', 30);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};
