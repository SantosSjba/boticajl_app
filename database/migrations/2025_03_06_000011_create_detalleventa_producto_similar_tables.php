<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('detalleventa')) {
            Schema::create('detalleventa', function (Blueprint $table) {
                $table->integer('idventa');
                $table->integer('item');
                $table->integer('idproducto');
                $table->decimal('cantidad', 10, 2);
                $table->decimal('valor_unitario', 10, 2);
                $table->decimal('precio_unitario', 10, 2);
                $table->decimal('igv', 10, 2);
                $table->decimal('porcentaje_igv', 10, 2);
                $table->decimal('valor_total', 10, 2);
                $table->decimal('importe_total', 10, 2);
                $table->decimal('descuento', 18, 2);

                $table->foreign('idventa')->references('idventa')->on('venta')->cascadeOnDelete()->cascadeOnUpdate();
                $table->foreign('idproducto')->references('idproducto')->on('productos');
            });
        }

        if (! Schema::hasTable('producto_similar')) {
            Schema::create('producto_similar', function (Blueprint $table) {
                $table->increments('idp_similar');
                $table->integer('idproducto');
                $table->string('producto');
                $table->string('presentacion');
                $table->foreign('idproducto')->references('idproducto')->on('productos')->cascadeOnDelete()->cascadeOnUpdate();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('producto_similar');
        Schema::dropIfExists('detalleventa');
    }
};
