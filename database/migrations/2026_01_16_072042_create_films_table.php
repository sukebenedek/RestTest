<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('films', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Film címe
            $table->string('director'); // Rendező
            $table->integer('release_year'); // Megjelenés éve
            $table->string('genre'); // Műfaj
            $table->float('rating', 3, 1)->nullable(); // Értékelés (pl. 8.5)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('films');
    }
};
