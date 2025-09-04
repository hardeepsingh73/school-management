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
        Schema::create('results', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('student_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');


            $table->foreignId('class_id')->constrained('school_classes')->onUpdate('cascade');

            // Result details
            $table->decimal('total_marks', 8, 2)->nullable();
            $table->decimal('obtained_marks', 8, 2)->nullable();
            $table->string('grade', 10)->nullable();


            // Tracking
            $table->foreignId('created_by')->constrained('users');

            // Timestamps
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index(['student_id']);
            $table->index('class_id');
            $table->index('grade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
