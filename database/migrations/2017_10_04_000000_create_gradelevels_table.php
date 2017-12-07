<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradelevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('los_gradelevels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('grade', 10)->unique();
            $table->string('grade_desc', 50)->unique();
            $table->unsignedInteger('report_order')->default(0)->unique();
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
        Schema::dropIfExists('los_gradelevels');
    }
}
