<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {

            /* =====================================================
             | PRIMARY
             ===================================================== */
            $table->id();
            $table->uuid('uuid')->unique();

            /* =====================================================
             | APPLICANT BASIC INFO
             ===================================================== */
            $table->string('name');
            $table->string('current_position')->nullable();
            $table->string('position_applied')->nullable();
            $table->string('item_number')->nullable();
            $table->unsignedBigInteger('station_school_id')->nullable();
            $table->string('sg_annual_salary')->nullable();
            $table->json('levels')->nullable();

            /* =====================================================
             | QS - EDUCATION
             ===================================================== */
            $table->text('qs_position_education')->nullable();
            $table->text('qs_applicant_education')->nullable();
            $table->text('remarks_education')->nullable();

            /* =====================================================
             | QS - TRAINING
             ===================================================== */
            $table->text('qs_position_training')->nullable();
            $table->text('qs_applicant_training')->nullable();
            $table->text('remarks_training')->nullable();

            /* =====================================================
             | QS - EXPERIENCE
             ===================================================== */
            $table->text('qs_position_experience')->nullable();
            $table->text('qs_applicant_experience')->nullable();
            $table->text('remarks_experience')->nullable();

            /* =====================================================
             | QS - ELIGIBILITY
             ===================================================== */
            $table->text('qs_position_eligibility')->nullable();
            $table->text('qs_applicant_eligibility')->nullable();
            $table->text('remarks_eligibility')->nullable();

            /* =====================================================
             | SYSTEM FIELDS
             ===================================================== */
            $table->string('status')->default('draft');
            $table->string('folder_path')->nullable();
            $table->timestamp('last_activity_at')->nullable();

            $table->timestamps();

            /* =====================================================
             | FOREIGN KEY
             ===================================================== */
            $table->foreign('station_school_id')
                ->references('id')
                ->on('schools')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('applications');
    }
};
