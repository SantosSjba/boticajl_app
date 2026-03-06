<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('venta')) {
            Schema::create('venta', function (Blueprint $table) {
                $table->increments('idventa');
                $table->integer('idconf');
                $table->char('tipocomp', 2);
                $table->integer('idcliente');
                $table->integer('idusuario');
                $table->integer('idserie');
                $table->dateTime('fecha_emision');
                $table->decimal('op_gravadas', 10, 2);
                $table->decimal('op_exoneradas', 10, 2);
                $table->decimal('op_inafectas', 10, 2);
                $table->decimal('igv', 10, 2);
                $table->decimal('total', 10, 2);
                $table->string('feestado', 50)->nullable();
                $table->char('fecodigoerror', 10)->nullable();
                $table->text('femensajesunat')->nullable();
                $table->string('nombrexml', 50)->nullable();
                $table->text('xmlbase64')->nullable();
                $table->text('cdrbase64')->nullable();
                $table->decimal('efectivo', 18, 2)->nullable();
                $table->decimal('vuelto', 18, 2)->nullable();
                $table->string('formadepago', 50);
                $table->char('tire', 50);
                $table->enum('estado', ['no_enviado', 'enviado', 'anulado']);
                $table->string('numope');

                $table->foreign('idconf')->references('idconfi')->on('configuracion')->cascadeOnDelete()->cascadeOnUpdate();
                $table->foreign('idcliente')->references('idcliente')->on('cliente')->cascadeOnDelete()->cascadeOnUpdate();
                $table->foreign('idusuario')->references('idusu')->on('usuario')->cascadeOnDelete()->cascadeOnUpdate();
                $table->foreign('idserie')->references('idserie')->on('serie')->cascadeOnDelete()->cascadeOnUpdate();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('venta');
    }
};
