<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',100);
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->string('semana',20);
            $table->integer('period_range_id')->unsigned();
            $table->foreign('period_range_id')
                  ->references('id')
                  ->on('periods_ranges')
                  ->onDelete('cascade');
            $table->integer('course_id')->unsigned();
            $table->foreign('course_id')
                    ->references('id')
                    ->on('courses')
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
        Schema::dropIfExists('lessons');
    }
}
