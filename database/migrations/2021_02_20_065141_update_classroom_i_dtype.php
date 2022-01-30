<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateClassroomIDtype extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classroom_students', function (Blueprint $table) {
            $table->dropForeign(['classrooms_id']);
        });
        Schema::table('classroom_coordinators', function (Blueprint $table) {
            $table->dropForeign(['classrooms_id']);
        });
        Schema::table('classrooms', function (Blueprint $table) {
            $table->string('id', 10)->change();
        });
        Schema::table('classroom_students', function (Blueprint $table) {
            $table->string('classrooms_id', 10)->change();
            $table->foreign('classrooms_id')->references('id')->on('classrooms')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
        Schema::table('classroom_coordinators', function (Blueprint $table) {
            $table->string('classrooms_id', 10)->change();
            $table->foreign('classrooms_id')->references('id')->on('classrooms')
                ->onUpdate('cascade')
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
    }
}
