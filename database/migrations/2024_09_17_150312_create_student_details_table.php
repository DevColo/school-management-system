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
        Schema::create('student_details', function (Blueprint $table) {
            $table->id();
            // Account Info
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Student Info
            $table->string('student_id');
            $table->string('first_name');
            $table->string('other_name')->nullable();
            $table->string('last_name');
            $table->string('gender')->nullable();
            $table->string('dob')->nullable();
            $table->string('pob')->nullable();
            $table->string('nationality')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            // Parents
            $table->string('mother_name')->nullable();
            $table->string('mother_phone')->nullable();
            $table->string('mother_email')->nullable();
            $table->string('father_name')->nullable();
            $table->string('father_phone')->nullable();
            $table->string('father_email')->nullable();
            // Emergency Contact Person
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_address')->nullable();
            $table->string('emergency_contact_email')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            
            $table->string('status');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_details');
    }
};
