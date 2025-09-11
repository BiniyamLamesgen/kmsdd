<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('endorsements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('skill_id')->constrained()->onDelete('cascade');
            $table->foreignId('endorsed_by')->constrained('employees')->onDelete('cascade');
            $table->date('endorsement_date')->nullable();
            $table->timestamps();
            
            // Ensure one endorsement per skill per endorser
            $table->unique(['employee_id', 'skill_id', 'endorsed_by']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('endorsements');
    }
};

