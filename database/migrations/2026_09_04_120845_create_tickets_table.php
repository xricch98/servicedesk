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
    Schema::create('tickets', function (Blueprint $table) {
        $table->id();
        $table->string('reference')->unique();
        $table->string('subject');
        $table->text('description');

        $table->foreignId('requester_id')->constrained('users');
        $table->foreignId('created_by_id')->constrained('users');
        $table->foreignId('assigned_to_id')->nullable()->constrained('users');

        $table->foreignId('department_id')->constrained();
        $table->foreignId('category_id')->nullable()->constrained();

        $table->string('status')->default('new');
        $table->string('priority')->default('medium');
        $table->string('location')->nullable();

        $table->timestamp('assigned_at')->nullable();
        $table->timestamp('first_response_at')->nullable();
        $table->timestamp('resolved_at')->nullable();
        $table->timestamp('closed_at')->nullable();

        $table->unsignedInteger('reopen_count')->default(0);
        $table->timestamps();

        $table->index(['department_id', 'status']);
        $table->index(['requester_id', 'status']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
