<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->string('model'); // Model name
            $table->unsignedBigInteger('model_id'); // ID of the model instance
            $table->string('action'); // Action performed
            $table->string('action_by'); // Who performed the action

            $table->json('new_model')->nullable(); // New model state (nullable)
            $table->json('old_model')->nullable(); // Old model state (nullable)
            $table->json('deleted_model')->nullable(); // Deleted model state (nullable)
            $table->dateTime('createdTime')->nullable(); // Time of creation (nullable)
            $table->dateTime('updatedTime')->nullable(); // Time of update (nullable)
            $table->dateTime('deletedTime')->nullable(); // Time of deletion (nullable)
            $table->timestamps(); // Adds created_at and updated_at columns
            $table->softDeletes(); // Adds 'deleted_at' column for soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
