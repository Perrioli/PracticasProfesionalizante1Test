<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('modulos', function (Blueprint $table) {
            // Esta columna guardará el ID del módulo que es prerrequisito
            $table->foreignId('prerequisite_modulo_id')->nullable()->after('plan_estudio_id')->constrained('modulos');
        });
    }
    public function down(): void
    {
        Schema::table('modulos', function (Blueprint $table) {
            $table->dropForeign(['prerequisite_modulo_id']);
            $table->dropColumn('prerequisite_modulo_id');
        });
    }
};
