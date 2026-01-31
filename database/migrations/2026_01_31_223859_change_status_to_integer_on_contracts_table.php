<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::table('contracts', function (Blueprint $table) {
            $table->tinyInteger('status_int')->default(1)->comment('Status of the contract (1: Draft, 2: Active, 3: Expired, 4: Terminated)')->after('status');
        });

        DB::table('contracts')->where('status', 'Draft')->update(['status_int' => 1]);
        DB::table('contracts')->where('status', 'Active')->update(['status_int' => 2]);

        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('contracts', function (Blueprint $table) {
            $table->renameColumn('status_int', 'status');
        });
    }

    public function down(): void {
        Schema::table('contracts', function (Blueprint $table) {
            $table->string('status_string')->nullable();
        });

        DB::table('contracts')->where('status', 1)->update(['status_string' => 'Draft']);

        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->renameColumn('status_string', 'status');
        });
    }
};
