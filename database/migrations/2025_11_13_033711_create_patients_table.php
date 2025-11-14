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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();

            // Personal Information
            $table->string('medical_record_number')->unique();
            $table->string('id_number')->nullable()->unique();
            $table->string('name');
            $table->string('place_of_birth')->nullable();
            $table->date('date_of_birth')->index();
            $table->string('gender')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('religion')->nullable();
            $table->string('blood_type')->nullable();
            $table->string('occupation')->nullable();

            // Contact Information
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relationship')->nullable();

            // Medical Information
            $table->text('allergies')->nullable();
            $table->text('medical_history')->nullable();

            // Administrative Information
            $table->enum('status', ['active', 'inactive', 'deceased'])->default('active')->index();
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
