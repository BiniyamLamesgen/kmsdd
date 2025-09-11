<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('position');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('internal_extension')->nullable();
            $table->string('profile_picture')->nullable();
            $table->date('date_joined')->nullable();

            // social links
            $table->string('linkedin')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('github')->nullable();
            $table->string('personal_website')->nullable();

            // additional metadata
            $table->string('languages')->nullable();
            $table->text('mentoring_interest')->nullable();
            $table->boolean('availability_for_sharing')->default(false);
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('restrict');

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('employees');
    }
};

