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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event'); // e.g., 'created', 'updated', 'deleted', 'viewed'
            $table->text('description');
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('subject_type', 191)->nullable(); // Reduced length
            $table->unsignedBigInteger('causer_id')->nullable();
            $table->string('causer_type', 191)->nullable();
            $table->json('properties')->nullable(); // Additional data
            $table->string('ip_address', 45)->nullable(); // Support for IPv6
            $table->string('user_agent')->nullable();
            $table->string('url')->nullable();
            $table->string('method')->nullable(); // HTTP method (GET, POST, etc.)
            $table->softDeletes();
            $table->timestamps();

            $table->index(['subject_id', 'subject_type']);
            $table->index(['causer_id', 'causer_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
