<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This method will be executed when you run `php artisan migrate`.
     */
    public function up()
    {
        // Create a new table called 'email_logs'
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key column (id)

            $table->string('to'); 
            // Stores the recipient's email address (required field)

            $table->string('subject')->nullable(); 
            // Email subject line (can be null if not provided)

            $table->text('body')->nullable(); 
            // Full email content/body (optional)

            $table->string('status')->default('sent'); 
            // Status of the email, e.g., 'sent' or 'failed' (defaults to 'sent')

            $table->text('error_message')->nullable(); 
            // Stores error message if email sending fails (optional)

            $table->softDeletes();
            $table->timestamps(); 
            // Automatically creates 'created_at' and 'updated_at' columns
        });
    }

    /**
     * Reverse the migrations.
     * This method rolls back the migration (deletes the table).
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs'); 
        // Drops the 'email_logs' table if it exists
    }
};
