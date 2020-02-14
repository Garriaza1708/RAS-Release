<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradeValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grade_values', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('grade');
            $table->integer('grade_structures_id')->unsigned();
            $table->foreign('grade_structures_id')
                  ->references('id')
                  ->on('grades_structures')
                  ->onDelete('cascade');
            $table->integer('student_id')->unsigned();
            $table->foreign('student_id')
                  ->references('id')
                  ->on('students')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grade_values');
    }
}
