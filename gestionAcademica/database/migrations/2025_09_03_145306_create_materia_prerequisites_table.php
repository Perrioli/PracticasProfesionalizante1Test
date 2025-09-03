<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Esta tabla pivote conectará una materia con sus prerrequisitos
        Schema::create('materia_prerequisites', function (Blueprint $table) {
            $table->primary(['materia_id', 'prerequisite_id']);
            $table->foreignId('materia_id')->constrained('materias')->onDelete('cascade');
            $table->foreignId('prerequisite_id')->constrained('materias')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materia_prerequisites');
    }
};
