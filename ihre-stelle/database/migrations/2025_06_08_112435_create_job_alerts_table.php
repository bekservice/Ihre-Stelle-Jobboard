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
        Schema::create('job_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('name')->nullable();
            $table->string('category')->nullable(); // Job-Kategorie
            $table->string('location')->nullable(); // Standort
            $table->json('job_types')->nullable(); // Array von Job-Typen (Vollzeit, Teilzeit, etc.)
            $table->string('frequency')->default('daily'); // daily, weekly, immediate
            $table->boolean('is_active')->default(true);
            $table->string('token')->unique(); // Für Abmeldung
            $table->timestamp('last_sent_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
            
            $table->index(['email', 'is_active']);
            $table->index(['category', 'is_active']);
            $table->index(['location', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_alerts');
    }
};
