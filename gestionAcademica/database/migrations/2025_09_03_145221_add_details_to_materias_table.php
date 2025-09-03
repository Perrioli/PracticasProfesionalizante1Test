<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materias', function (Blueprint $table) {
            // Añadimos las nuevas columnas
            $table->string('codigo')->nullable()->after('id');
            $table->enum('regimen', ['Anual', 'Cuatrimestral', 'Bimestral'])->default('Anual')->after('carga_horaria_total');
        });
    }

    public function down(): void
    {
        Schema::table('materias', function (Blueprint $table) {
            // Operación inversa para poder hacer rollback
            $table->dropColumn(['codigo', 'regimen']);
        });
    }
};
