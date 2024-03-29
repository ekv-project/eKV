<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSemesterRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('semester_registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('semester_sessions_id');
            $table->foreign('semester_sessions_id')->references('id')->on('semester_sessions')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('users_username');
            $table->foreign('users_username')->references('username')->on('users')
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
        Schema::dropIfExists('semester_registrations');
    }
}
