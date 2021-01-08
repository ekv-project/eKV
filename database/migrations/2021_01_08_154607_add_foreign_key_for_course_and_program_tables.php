<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyForCourseAndProgramTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_grades', function (Blueprint $table) {
            $table->foreign('users_username')->references('username')->on('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreign('courses_code')->references('code')->on('courses')
            ->onUpdate('cascade')
            ->onDelete('cascade');    
            $table->foreign('study_levels_code')->references('code')->on('study_levels')
            ->onUpdate('cascade')
            ->onDelete('cascade');   
        });
        Schema::table('semester_grades', function (Blueprint $table) {
            $table->foreign('users_username')->references('username')->on('users')
            ->onUpdate('cascade')
            ->onDelete('cascade'); 
            $table->foreign('study_levels_code')->references('code')->on('study_levels')
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
        //
    }
}
