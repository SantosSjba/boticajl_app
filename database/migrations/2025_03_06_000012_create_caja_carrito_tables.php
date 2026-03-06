<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('caja_apertura')) {
            Schema::create('caja_apertura', function (Blueprint $table) {
                $table->increments('idcaja_a');
                $table->date('fecha');
                $table->string('caja');
                $table->string('turno');
                $table->string('hora');
                $table->decimal('monto', 10, 2);
                $table->string('usuario');
                $table->enum('estado', ['Abierto', 'Cerrado']);
            });
        }

        if (! Schema::hasTable('caja_cierre')) {
            Schema::create('caja_cierre', function (Blueprint $table) {
                $table->increments('idcaja_c');
                $table->date('fecha');
                $table->string('caja');
                $table->string('turno');
                $table->string('hora');
                $table->string('usuario');
                $table->decimal('pagos_efectivo', 10, 2);
                $table->decimal('pagos_tarjeta', 10, 2);
                $table->string('pagos_deposito');
                $table->decimal('total_venta', 10, 2);
                $table->decimal('monto_a', 10, 2);
                $table->decimal('caja_sistema', 10, 2);
                $table->decimal('efectivo_caja', 10, 2);
                $table->decimal('diferencia', 10, 2);
            });
        }

        if (! Schema::hasTable('carrito')) {
            Schema::create('carrito', function (Blueprint $table) {
                $table->integer('idproducto');
                $table->string('descripcion');
                $table->string('presentacion');
                $table->decimal('cantidad', 10, 2);
                $table->decimal('valor_unitario', 10, 2);
                $table->decimal('precio_unitario', 10, 2);
                $table->decimal('igv', 10, 2);
                $table->decimal('porcentaje_igv', 10, 2);
                $table->decimal('valor_total', 10, 2);
                $table->decimal('importe_total', 10, 2);
                $table->string('operacion', 100);
                $table->string('session_id');
            });
        }

        if (! Schema::hasTable('carritoc')) {
            Schema::create('carritoc', function (Blueprint $table) {
                $table->integer('idproducto');
                $table->string('descripcion');
                $table->string('presentacion');
                $table->integer('cantidad');
                $table->decimal('precio', 18, 2);
                $table->decimal('importe', 18, 2);
                $table->string('session_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('carritoc');
        Schema::dropIfExists('carrito');
        Schema::dropIfExists('caja_cierre');
        Schema::dropIfExists('caja_apertura');
    }
};
