<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ppst_indicators', function (Blueprint $table) {

            $table->enum('indicator_type', ['COI','NCOI'])
                  ->after('domain')
                  ->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('ppst_indicators', function (Blueprint $table) {

            $table->dropColumn('indicator_type');

        });
    }
};
