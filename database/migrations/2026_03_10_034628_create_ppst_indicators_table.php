<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ppst_indicators', function (Blueprint $table) {
            $table->id();
            $table->string('position_level'); // e.g., "Teacher I – MT I"
            $table->unsignedTinyInteger('domain'); // 1-7
            $table->text('indicator_text');
            $table->unsignedTinyInteger('order'); // 1,2,3...
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppst_indicators');
    }
};
