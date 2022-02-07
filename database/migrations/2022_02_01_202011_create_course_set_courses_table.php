<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseSetCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_set_courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_sets_id');
            $table->string('courses_code');
            $table->foreign('course_sets_id')->references('id')->on('course_sets')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('courses_code')->references('code')->on('courses')
                ->onUpdate('cascade')
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
        Schema::dropIfExists('course_set_courses');
    }
}
