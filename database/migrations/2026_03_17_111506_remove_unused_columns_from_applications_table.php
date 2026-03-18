<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {

            $table->dropColumn([

                'qs_position_education',
                'qs_applicant_education',
                'remarks_education',

                'qs_position_training',
                'qs_applicant_training',
                'remarks_training',

                'qs_position_experience',
                'qs_applicant_experience',
                'remarks_experience',

                'qs_position_eligibility',
                'qs_applicant_eligibility',
                'remarks_eligibility',

                'folder_path',
                'last_activity_at'

            ]);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {

            $table->text('qs_position_education')->nullable();
            $table->text('qs_applicant_education')->nullable();
            $table->text('remarks_education')->nullable();

            $table->text('qs_position_training')->nullable();
            $table->text('qs_applicant_training')->nullable();
            $table->text('remarks_training')->nullable();

            $table->text('qs_position_experience')->nullable();
            $table->text('qs_applicant_experience')->nullable();
            $table->text('remarks_experience')->nullable();

            $table->text('qs_position_eligibility')->nullable();
            $table->text('qs_applicant_eligibility')->nullable();
            $table->text('remarks_eligibility')->nullable();

            $table->string('folder_path')->nullable();
            $table->timestamp('last_activity_at')->nullable();

        });
    }
};