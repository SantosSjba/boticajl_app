<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Tipos de afectación IGV (SUNAT). IDs y códigos coinciden con la BD existente factosys_boticajl.
 * tabla: tipo_afectacion (idtipoa PK, codigo, descripcion, codigo_afectacion, nombre_afectacion, tipo_afectacion)
 */
class TipoAfectacionSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('tipo_afectacion')->exists()) {
            return;
        }

        DB::table('tipo_afectacion')->insert([
            ['idtipoa' => 1, 'codigo' => '10', 'descripcion' => 'Gravado', 'codigo_afectacion' => '1000', 'nombre_afectacion' => 'GRA', 'tipo_afectacion' => 'VAT'],
            ['idtipoa' => 2, 'codigo' => '20', 'descripcion' => 'Exonerado', 'codigo_afectacion' => '9997', 'nombre_afectacion' => 'EXO', 'tipo_afectacion' => 'FRE'],
            ['idtipoa' => 3, 'codigo' => '30', 'descripcion' => 'Inafecto', 'codigo_afectacion' => '9998', 'nombre_afectacion' => 'INA', 'tipo_afectacion' => 'FRE'],
        ]);
    }
}
