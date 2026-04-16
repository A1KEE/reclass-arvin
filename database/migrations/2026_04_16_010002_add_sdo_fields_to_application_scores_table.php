<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('application_scores', function (Blueprint $table) {

            $table->string('sdo_from_position')->nullable();
            $table->string('sdo_from_grade')->nullable();

            $table->string('sdo_to_position')->nullable();
            $table->string('sdo_to_grade')->nullable();

            $table->date('sdo_date_processed')->nullable();
            $table->string('sdo_remarks')->nullable();

        });
    }

    public function down()
    {
        Schema::table('application_scores', function (Blueprint $table) {

            $table->dropColumn([
                'sdo_from_position',
                'sdo_from_grade',
                'sdo_to_grade',
                'sdo_to_position',
                'sdo_date_processed',
                'sdo_remarks'
            ]);

        });
    }
};
