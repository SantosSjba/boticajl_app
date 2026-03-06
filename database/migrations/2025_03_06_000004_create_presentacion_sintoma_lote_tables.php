<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('presentacion')) {
            Schema::create('presentacion', function (Blueprint $table) {
                $table->increments('idpresentacion');
                $table->string('presentacion');
                $table->integer('idsucu_c');
            });
        }

        if (! Schema::hasTable('sintoma')) {
            Schema::create('sintoma', function (Blueprint $table) {
                $table->increments('idsintoma');
                $table->string('sintoma');
                $table->integer('idsucu_c');
            });
        }

        if (! Schema::hasTable('lote')) {
            Schema::create('lote', function (Blueprint $table) {
                $table->increments('idlote');
                $table->string('numero');
                $table->date('fecha_vencimiento');
                $table->integer('idsucu_c');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('lote');
        Schema::dropIfExists('sintoma');
        Schema::dropIfExists('presentacion');
    }
};
