<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('class_name')->nullable();
            $table->string('class_room')->nullable();
            $table->integer('student_code')->unique();
            $table->integer('tutor_code')->unique();
            $table->integer('tutor_creator_id')->nullable();
            $table->timestamp('last_accessed');
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
        Schema::drop('class_sessions');
    }
}
