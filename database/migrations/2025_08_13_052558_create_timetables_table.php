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
        Schema::create('timetables', function (Blueprint $table) {
            $table->id();

            // Relationships

            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');


            // Schedule details
            $table->tinyInteger('day_of_week')->comment('0:Sunday, 1:Monday, ..., 6:Saturday');

            $table->time('start_time');
            $table->time('end_time');
            $table->string('room', 20)->nullable();

            // Recurrence and type
            $table->tinyInteger('schedule_type')->default(1)->comment('1:regular, 2:makeup, 3:special');

            $table->date('effective_from')->nullable();
            $table->date('effective_until')->nullable();

            // Timestamps
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index([ 'day_of_week']);
            $table->index(['teacher_id', 'day_of_week']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timetables');
    }
};
