<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Monedas (tabla sin PK; codigo UNIQUE). Coincide con la BD existente factosys_boticajl.
 * tabla: moneda (codigo char(3) UNIQUE, descripcion varchar(100))
 */
class MonedaSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('moneda')->exists()) {
            return;
        }

        DB::table('moneda')->insert([
            ['codigo' => 'PEN', 'descripcion' => 'Soles'],
            ['codigo' => 'USD', 'descripcion' => 'Dólares'],
        ]);
    }
}
