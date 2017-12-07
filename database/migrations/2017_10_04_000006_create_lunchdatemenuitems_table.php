<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Silber\Bouncer\Database\Models;

class CreateLunchDateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('los_lunchdate_menuitems', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lunchdate_id')->index();
            $table->unsignedInteger('menuitem_id')->index();
            $table->timestamps();

            $table->foreign('lunchdate_id')->references('id')->on(Models::table('los_lunchdates'))
                ->onUpdate('restrict')->onDelete('restrict');

            $table->foreign('menuitem_id')->references('id')->on(Models::table('los_menuitems'))
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
        Schema::dropIfExists('los_lunchdate_menuitems');
    }
}
