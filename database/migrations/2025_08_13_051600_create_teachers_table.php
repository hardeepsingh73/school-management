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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();

            // User relationship (for authentication only)
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');


            // Department information
            $table->foreignId('department_id')->constrained('departments')->onDelete('restrict');

            // Professional details
            $table->string('designation', 50)->nullable();
            $table->json('additional_information')->nullable();
            // Timestamps
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index('department_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
