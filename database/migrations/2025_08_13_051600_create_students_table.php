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
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            // Authentication relationship
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');

            // Academic structure
            $table->foreignId('school_class_id')->constrained('school_classes')->comment('References class-section combination');

            $table->json('additional_information')->nullable();

            // Timestamps and soft deletes
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index(['school_class_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
