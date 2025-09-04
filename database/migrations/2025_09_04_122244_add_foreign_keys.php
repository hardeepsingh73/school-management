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
        Schema::table('exams', function (Blueprint $table) {
            $table->foreignId('class_id')->nullable()->constrained('school_classes')->onDelete('set null');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->foreignId('recorded_by')->nullable()->constrained('users')->onDelete('set null');
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->index('department_id');
        });

        Schema::table('timetables', function (Blueprint $table) {
            $table->foreignId('class_id')->constrained('school_classes')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->index('class_id');
        });

        Schema::table('results', function (Blueprint $table) {
            $table->foreignId('exam_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('school_classes')->onUpdate('cascade')->onDelete('cascade');
            $table->index('exam_id');
            $table->index('class_id');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('school_class_id')->constrained('school_classes')->comment('References class-section combination');
            $table->index('school_class_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('profile_image_id')->nullable();
            $table->foreign('profile_image_id')->references('id')->on('files')->onDelete('set null');
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->foreignId('department_id')->constrained('departments')->onDelete('restrict');
            $table->index('department_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropIndex(['department_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['profile_image_id']);
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['school_class_id']);
            $table->dropIndex(['school_class_id']);
        });

        Schema::table('results', function (Blueprint $table) {
            $table->dropForeign(['exam_id']);
            $table->dropForeign(['class_id']);
            $table->dropIndex(['exam_id']);
            $table->dropIndex(['class_id']);
        });

        Schema::table('timetables', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->dropForeign(['subject_id']);
            $table->dropIndex(['class_id']);
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropIndex(['department_id']);
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['recorded_by']);
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
        });
    }
};
