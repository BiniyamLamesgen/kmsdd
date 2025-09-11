<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('knowledge_sharings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('document_title');
            $table->string('document_type')->nullable();
            $table->string('link')->nullable();
            $table->date('date_shared')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('knowledge_sharings');
    }
};

