<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tipo_documento')) {
            Schema::create('tipo_documento', function (Blueprint $table) {
                $table->integer('idtipo_docu')->primary();
                $table->char('codigo', 1);
                $table->string('descripcion', 50);
            });
        }

        if (! Schema::hasTable('tipo_afectacion')) {
            Schema::create('tipo_afectacion', function (Blueprint $table) {
                $table->integer('idtipoa')->primary();
                $table->char('codigo', 2);
                $table->string('descripcion', 50)->nullable();
                $table->char('codigo_afectacion', 4)->nullable();
                $table->char('nombre_afectacion', 3)->nullable();
                $table->char('tipo_afectacion', 3)->nullable();
            });
        }

        if (! Schema::hasTable('tipo_comprobante')) {
            Schema::create('tipo_comprobante', function (Blueprint $table) {
                $table->char('codigo', 2)->primary();
                $table->string('descripcion', 100);
            });
        }

        if (! Schema::hasTable('unidad')) {
            Schema::create('unidad', function (Blueprint $table) {
                $table->integer('iduni')->primary();
                $table->char('codigo', 3);
                $table->string('descripcion', 50)->nullable();
            });
        }

        if (! Schema::hasTable('moneda')) {
            Schema::create('moneda', function (Blueprint $table) {
                $table->char('codigo', 3)->unique();
                $table->string('descripcion', 100);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('moneda');
        Schema::dropIfExists('unidad');
        Schema::dropIfExists('tipo_comprobante');
        Schema::dropIfExists('tipo_afectacion');
        Schema::dropIfExists('tipo_documento');
    }
};
