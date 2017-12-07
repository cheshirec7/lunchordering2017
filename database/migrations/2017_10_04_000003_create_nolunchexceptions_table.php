<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Silber\Bouncer\Database\Models;

class CreateNoLunchExceptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('los_nolunchexceptions', function (Blueprint $table) {
            $table->increments('id');
            $table->date('exception_date');
            $table->string('reason', 30);
            $table->string('description', 50);
            $table->unsignedInteger('grade_id')->default(1)->index();
            $table->unsignedInteger('teacher_id')->default(1)->index();
            $table->timestamps();

            $table->unique(array('exception_date', 'grade_id', 'teacher_id'));

            $table->foreign('grade_id')->references('id')->on(Models::table('los_gradelevels'))
                ->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('los_nolunchexceptions');
    }
}
