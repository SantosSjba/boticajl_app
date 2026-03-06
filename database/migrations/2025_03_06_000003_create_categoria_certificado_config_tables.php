<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('categoria')) {
            Schema::create('categoria', function (Blueprint $table) {
                $table->increments('idcategoria');
                $table->string('forma_farmaceutica');
                $table->string('ff_simplificada');
            });
        }

        if (! Schema::hasTable('certificado')) {
            Schema::create('certificado', function (Blueprint $table) {
                $table->increments('idcertificado');
                $table->string('certificado')->nullable();
                $table->string('clave_certificado')->nullable();
                $table->string('estado')->nullable();
            });
        }

        if (! Schema::hasTable('confi_backup')) {
            Schema::create('confi_backup', function (Blueprint $table) {
                $table->integer('idbackup')->primary();
                $table->string('host', 50);
                $table->string('db_name', 50);
                $table->string('user', 50);
                $table->string('pass', 50);
            });
        }

        if (! Schema::hasTable('configuracion')) {
            Schema::create('configuracion', function (Blueprint $table) {
                $table->increments('idconfi');
                $table->string('logo');
                $table->char('tipodoc', 1);
                $table->string('ruc');
                $table->string('razon_social');
                $table->string('nombre_comercial');
                $table->string('direccion');
                $table->string('pais');
                $table->string('departamento');
                $table->string('provincia');
                $table->string('distrito')->default('NULL');
                $table->char('ubigeo', 6);
                $table->string('usuario_sol', 50);
                $table->string('clave_sol', 50);
                $table->char('simbolo_moneda', 10);
                $table->decimal('impuesto', 10, 2);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracion');
        Schema::dropIfExists('confi_backup');
        Schema::dropIfExists('certificado');
        Schema::dropIfExists('categoria');
    }
};
