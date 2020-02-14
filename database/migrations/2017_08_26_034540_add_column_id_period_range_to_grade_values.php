<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIdPeriodRangeToGradeValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('grade_values', function (Blueprint $table) {
            $table->integer('period_ranges_id')->unsigned()->after('student_id');
            $table->foreign('period_ranges_id')
                  ->references('id')
                  ->on('periods_ranges')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('grade_values', function (Blueprint $table) {
            $table->dropForeign('grade_values_period_ranges_id_foreign');
            $table->dropColumn('period_ranges_id');
        });
    }
}
