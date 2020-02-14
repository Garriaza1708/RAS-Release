<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeriodsRangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periods_ranges', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->integer('duracion');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->integer('period_id')->unsigned();
            $table->foreign('period_id')->references('id')->on('periods')
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
        Schema::dropIfExists('periods_ranges');
    }
}
