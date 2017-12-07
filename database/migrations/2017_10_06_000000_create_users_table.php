<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Silber\Bouncer\Database\Models;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('los_users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('account_id')->index();
            $table->string('first_name');
            $table->string('last_name');
            $table->unsignedTinyInteger('user_type')->default(3);
            $table->unsignedInteger('grade_id')->default(1)->index();
            $table->unsignedInteger('teacher_id')->default(1)->index();
            $table->boolean('allowed_to_order')->default(true);
            $table->timestamps();

            $table->unique(array('account_id', 'first_name', 'last_name'));

            $table->foreign('account_id')->references('id')->on(Models::table('los_accounts'))
                ->onUpdate('restrict')->onDelete('restrict');

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
        Schema::dropIfExists('los_users');
    }
}
