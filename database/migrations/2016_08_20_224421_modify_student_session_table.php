<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyStudentSessionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('student_sessions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('class_id')->unsigned()->change();
            $table->foreign('class_id')
                    ->references('id')
                    ->on('class_sessions')
                    ->onDelete('cascade');
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_sessions', function (Blueprint $table) {
            //
            $table->dropForeign(['class_id']);
        });
    }
}
