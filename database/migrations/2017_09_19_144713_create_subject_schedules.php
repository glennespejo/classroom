<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectSchedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject_code')->nullable();
            $table->string('subject_name')->nullable();;
            $table->string('day')->nullable();;
            $table->string('time_start')->nullable();;
            $table->string('time_end')->nullable();;
            $table->string('teacher_id')->nullable();
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
        Schema::dropIfExists('subject_schedules');
    }
}
