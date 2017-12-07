<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Silber\Bouncer\Database\Models;

class CreateLunchDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('los_lunchdates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('provider_id')->index();
            $table->date('provide_date');
            $table->datetime('orders_placed')->nullable();
            $table->string('additional_text', 50)->nullable();
            $table->string('extended_care_text', 50)->nullable();
            $table->timestamps();

            $table->unique(array('provider_id', 'provide_date'));

            $table->foreign('provider_id')->references('id')->on(Models::table('los_providers'))
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
        Schema::dropIfExists('los_lunchdates');
    }
}
