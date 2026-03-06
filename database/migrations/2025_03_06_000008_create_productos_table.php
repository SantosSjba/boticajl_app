<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('productos')) {
            Schema::create('productos', function (Blueprint $table) {
                $table->increments('idproducto');
                $table->string('codigo')->nullable();
                $table->integer('idlote');
                $table->string('descripcion');
                $table->string('tipo', 50);
                $table->integer('stock');
                $table->integer('stockminimo');
                $table->decimal('precio_compra', 18, 2);
                $table->decimal('precio_venta', 10, 2);
                $table->decimal('descuento', 18, 2)->nullable();
                $table->string('ventasujeta', 50);
                $table->date('fecha_registro');
                $table->string('reg_sanitario')->nullable();
                $table->integer('idcategoria');
                $table->integer('idpresentacion');
                $table->integer('idcliente');
                $table->integer('idsintoma');
                $table->integer('idunidad');
                $table->integer('idtipoaf');
                $table->string('estado', 50);
                $table->char('tipo_precio', 2);

                $table->foreign('idcategoria')->references('idcategoria')->on('categoria')->cascadeOnDelete()->cascadeOnUpdate();
                $table->foreign('idpresentacion')->references('idpresentacion')->on('presentacion');
                $table->foreign('idcliente')->references('idcliente')->on('cliente')->cascadeOnDelete()->cascadeOnUpdate();
                $table->foreign('idsintoma')->references('idsintoma')->on('sintoma')->cascadeOnDelete()->cascadeOnUpdate();
                $table->foreign('idlote')->references('idlote')->on('lote')->cascadeOnDelete()->cascadeOnUpdate();
                $table->foreign('idunidad')->references('iduni')->on('unidad')->cascadeOnDelete()->cascadeOnUpdate();
                $table->foreign('idtipoaf')->references('idtipoa')->on('tipo_afectacion')->cascadeOnDelete()->cascadeOnUpdate();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
