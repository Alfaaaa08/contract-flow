<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::table('contract_types', function (Blueprint $table) {
            $table->string('icon')->default('file-text');
        });
    }

    public function down(): void {
        Schema::table('contract_types', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
    }
};
