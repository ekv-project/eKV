<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSemesterGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('semester_grades', function (Blueprint $table) {
            $table->id();
            $table->string('users_username');
            $table->string('study_levels_code');
            $table->string('semester');
            $table->string('total_credit_gpa');
            $table->string('total_credit_cgpa');
            $table->string('gpa');
            $table->string('cgpa');
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
        Schema::dropIfExists('semester_grades');
    }
}
