<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('desk')->nullable();
            $table->integer('class_id');
            $table->integer('student_id')->nullable();
            $table->timestamp('last_accessed');
            $table->timestamp('asked_for_help')->nullable();
            $table->boolean('needs_help');
            $table->string('help_reason')->nullable();
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
        Schema::drop('student_sessions');
    }
}
