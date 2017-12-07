<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Silber\Bouncer\Database\Models;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('los_menuitems', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('provider_id')->index();
            $table->string('item_name', 50);
            $table->string('description');
            $table->unsignedInteger('price');
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->unique(array('item_name', 'provider_id'));

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
        Schema::dropIfExists('los_menuitems');
    }
}
