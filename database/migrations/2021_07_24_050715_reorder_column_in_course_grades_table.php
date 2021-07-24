<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReorderColumnInCourseGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("SET sql_mode = '';");
        DB::statement("ALTER TABLE course_grades MODIFY COLUMN created_at TIMESTAMP AFTER grade_pointer");
        DB::statement("ALTER TABLE course_grades MODIFY COLUMN updated_at TIMESTAMP AFTER created_at");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("SET sql_mode = '';");
        DB::statement("ALTER TABLE course_grades MODIFY COLUMN created_at TIMESTAMP AFTER semester");
        DB::statement("ALTER TABLE course_grades MODIFY COLUMN updated_at TIMESTAMP AFTER created_at");
    }
}
