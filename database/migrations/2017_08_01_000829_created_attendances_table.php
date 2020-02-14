<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatedAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('estado',['Asistencia','Tardanza','Falta']);
            $table->integer('lesson_id')->unsigned();
            $table->foreign('lesson_id')
                  ->references('id')
                  ->on('lessons')
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
        Schema::dropIfExists('attendances');
    }
}
