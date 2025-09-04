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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();


            // Academic Information
            $table->foreignId('subject_id')->nullable()->constrained();

            // Date Information
            $table->date('exam_date');

            // Status & Type (using integers)
            $table->tinyInteger('type')->default(1)->comment('1:weekly, 2:monthly, 3:quarterly, 4:semester, 5:final');
           
            $table->json('additional_information')->nullable();

            // Timestamps
            $table->softDeletes();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
