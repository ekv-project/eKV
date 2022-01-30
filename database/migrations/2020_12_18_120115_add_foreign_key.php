<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classrooms', function (Blueprint $table) {
            $table->foreign('programs_code')->references('code')->on('programs')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('study_levels_code')->references('code')->on('study_levels')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
        Schema::table('classroom_coordinators', function (Blueprint $table) {
            $table->foreign('users_username')->references('username')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('classrooms_id')->references('id')->on('classrooms')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
        Schema::table('classroom_students', function (Blueprint $table) {
            $table->foreign('users_username')->references('username')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('classrooms_id')->references('id')->on('classrooms')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
        Schema::table('login_activities', function (Blueprint $table) {
            $table->foreign('users_username')->references('username')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->foreign('users_username')->references('username')->on('users')
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
