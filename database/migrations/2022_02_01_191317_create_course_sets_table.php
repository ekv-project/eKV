<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_sets', function (Blueprint $table) {
            $table->id();
            $table->string('study_levels_code');
            $table->string('programs_code');
            $table->foreign('study_levels_code')->references('code')->on('study_levels')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('programs_code')->references('code')->on('programs')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('semester');
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
        Schema::dropIfExists('course_sets');
    }
}
