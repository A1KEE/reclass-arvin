<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // 1️⃣ Alisin foreign key constraint
            $table->dropForeign(['station_school_id']);

            // 2️⃣ Alisin ang column
            $table->dropColumn('station_school_id');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->bigInteger('station_school_id')->unsigned()->nullable();
            $table->foreign('station_school_id')->references('id')->on('schools');
        });
    }
};