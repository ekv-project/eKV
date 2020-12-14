<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('users_username');
            $table->string('identification_number');
            $table->string('phone_number');
            $table->date('date_of_birth');
            $table->string('place_of_birth');
            $table->string('home_address');
            $table->string('home_number');
            $table->string('guardian_name');
            $table->string('guardian_phone_number');
            $table->bigInteger('classrooms_id')->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('user_profiles');
    }
}
