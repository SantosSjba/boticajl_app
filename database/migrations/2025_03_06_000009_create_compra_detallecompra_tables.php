<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('compra')) {
            Schema::create('compra', function (Blueprint $table) {
                $table->increments('idcompra');
                $table->integer('idcliente');
                $table->date('fecha');
                $table->decimal('subtotal', 18, 2);
                $table->decimal('igv', 18, 2);
                $table->decimal('total', 18, 2);
                $table->string('docu', 30);
                $table->char('num_docu', 50);
                $table->foreign('idcliente')->references('idcliente')->on('cliente')->cascadeOnDelete()->cascadeOnUpdate();
            });
        }

        if (! Schema::hasTable('detallecompra')) {
            Schema::create('detallecompra', function (Blueprint $table) {
                $table->integer('idcompra');
                $table->integer('idproducto');
                $table->decimal('cantidad', 18, 2);
                $table->decimal('precio', 18, 2);
                $table->decimal('importe', 18, 2);
                $table->foreign('idcompra')->references('idcompra')->on('compra');
                $table->foreign('idproducto')->references('idproducto')->on('productos');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('detallecompra');
        Schema::dropIfExists('compra');
    }
};
