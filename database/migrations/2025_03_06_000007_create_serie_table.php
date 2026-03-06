<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('serie')) {
            Schema::create('serie', function (Blueprint $table) {
                $table->integer('idserie')->primary();
                $table->char('tipocomp', 2)->nullable();
                $table->char('serie', 4)->nullable();
                $table->integer('correlativo')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('serie');
    }
};
