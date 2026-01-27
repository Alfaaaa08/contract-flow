<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->index(); 
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->string('name');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('contract_type_id')->constrained()->onDelete('cascade');
            $table->decimal('value', 15, 2)->default(0);
            $table->date('end_date');
            $table->string('file_path')->nullable();
            $table->string('status')->default('Draft');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('contracts');
    }
};
