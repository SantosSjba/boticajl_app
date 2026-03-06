<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Tipos de documento (SUNAT). IDs y códigos deben coincidir con la BD existente factosys_boticajl.
 * tabla: tipo_documento (idtipo_docu PK, codigo char(1), descripcion varchar(50))
 */
class TipoDocumentoSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('tipo_documento')->exists()) {
            return;
        }

        DB::table('tipo_documento')->insert([
            ['idtipo_docu' => 1, 'codigo' => '1', 'descripcion' => 'DNI'],
            ['idtipo_docu' => 2, 'codigo' => '6', 'descripcion' => 'RUC'],
            ['idtipo_docu' => 3, 'codigo' => '4', 'descripcion' => 'Carné de extranjería'],
        ]);
    }
}
