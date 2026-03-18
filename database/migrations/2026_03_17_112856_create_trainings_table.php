<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('application_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('title');
            $table->string('type');

            $table->date('start_date');
            $table->date('end_date');

            $table->integer('hours')->default(0);

            $table->string('file_path')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
