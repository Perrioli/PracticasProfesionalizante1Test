<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materia_submateria', function (Blueprint $table) {
            $table->primary(['materia_id', 'submateria_id']);
            $table->foreignId('materia_id')->constrained('materias')->onDelete('cascade');
            $table->foreignId('submateria_id')->constrained('submaterias')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materia_submateria');
    }
};
