<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Silber\Bouncer\Database\Models;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('los_orderdetails', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('account_id')->index();
            $table->unsignedInteger('order_id')->index();
            $table->unsignedInteger('menuitem_id')->index();
            $table->unsignedInteger('provider_id')->index();
            $table->unsignedTinyInteger('qty')->default(1);
            $table->unsignedInteger('price')->default(0);
            $table->timestamps();

            $table->unique(array('order_id', 'menuitem_id'));

            $table->foreign('account_id')->references('id')->on(Models::table('los_accounts'))
                ->onUpdate('restrict')->onDelete('restrict');

            $table->foreign('order_id')->references('id')->on(Models::table('los_orders'))
                ->onUpdate('restrict')->onDelete('restrict');

            $table->foreign('menuitem_id')->references('id')->on(Models::table('los_menuitems'))
                ->onUpdate('restrict')->onDelete('restrict');

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
        Schema::dropIfExists('los_orderdetails');
    }
}
