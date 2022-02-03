<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSemesterSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('semester_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_sets_id');
            $table->foreign('course_sets_id')->references('id')->on('course_sets')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('session', 2);
            $table->string('year', 5);
            // Open
            // Close
            $table->string('status', 10);
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
        Schema::dropIfExists('semester_sessions');
    }
}
