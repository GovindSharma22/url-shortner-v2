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
        Schema::create('invites', function (Blueprint $table) {
        $table->id();
        $table->foreignId('client_id')->nullable()->constrained();
        $table->string('email')->unique();
        $table->string('token');
        $table->enum('role', ['admin', 'member']);
        $table->boolean('accepted')->default(false);
        $table->timestamp('expires_at');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invites');
    }
};
