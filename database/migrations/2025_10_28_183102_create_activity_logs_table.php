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
            $table->morphs('user'); // user_id and user_type (includes index)
            $table->string('action'); // login, logout, created, updated, deleted, etc.
            $table->string('subject_type')->nullable(); // Model type
            $table->unsignedBigInteger('subject_id')->nullable(); // Model ID
            $table->text('description')->nullable();
            $table->json('properties')->nullable(); // Additional data
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('session_id')->nullable();
            $table->timestamps();
            
            // Additional indexes
            $table->index(['subject_type', 'subject_id']);
            $table->index('action');
            $table->index('created_at');
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
