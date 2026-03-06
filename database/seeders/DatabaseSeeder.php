<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Ejecuta los seeders de catálogos base (tipo_documento, tipo_afectacion, unidad, moneda).
     */
    public function run(): void
    {
        $this->call([
            TipoDocumentoSeeder::class,
            TipoAfectacionSeeder::class,
            UnidadSeeder::class,
            MonedaSeeder::class,
        ]);
    }
}
