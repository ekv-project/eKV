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
            $table->date('date_of_birth');
            $table->string('place_of_birth');
            $table->string('home_address');
            $table->string('phone_number');
            $table->string('home_number');
            $table->string('guardian_name');
            $table->string('guardian_phone_number');
            $table->bigInteger('classrooms_id');
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
        Schema::dropIfExists('user_profiles');
    }
}
