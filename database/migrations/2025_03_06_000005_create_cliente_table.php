<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('cliente')) {
            Schema::create('cliente', function (Blueprint $table) {
                $table->increments('idcliente');
                $table->string('nombres');
                $table->integer('id_tipo_docu');
                $table->string('direccion')->nullable();
                $table->string('nrodoc', 30)->default('NULL');
                $table->enum('tipo', ['cliente', 'laboratorio']);
                $table->foreign('id_tipo_docu')->references('idtipo_docu')->on('tipo_documento')->cascadeOnDelete()->cascadeOnUpdate();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cliente');
    }
};
