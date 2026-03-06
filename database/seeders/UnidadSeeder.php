<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Unidades de medida (SUNAT). iduni 1 = NIU (UNIDAD) es el valor por defecto en productos.
 * tabla: unidad (iduni PK, codigo char(3), descripcion varchar(50))
 */
class UnidadSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('unidad')->exists()) {
            return;
        }

        DB::table('unidad')->insert([
            ['iduni' => 1, 'codigo' => 'NIU', 'descripcion' => 'UNIDAD'],
            ['iduni' => 2, 'codigo' => 'BX', 'descripcion' => 'CAJA'],
            ['iduni' => 3, 'codigo' => 'FBO', 'descripcion' => 'FRASCO'],
            ['iduni' => 4, 'codigo' => 'TUB', 'descripcion' => 'TUBO'],
            ['iduni' => 5, 'codigo' => 'BLI', 'descripcion' => 'BLISTER'],
        ]);
    }
}
