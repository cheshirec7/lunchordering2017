<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Silber\Bouncer\Database\Models;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('los_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('account_id')->index();
            $table->unsignedInteger('user_id')->index();
            $table->unsignedInteger('lunchdate_id')->index();
            $table->date('order_date');
            $table->string('short_desc');
            $table->unsignedInteger('total_price')->default(0);
            $table->unsignedTinyInteger('status_code')->default(0);
            $table->unsignedInteger('entered_by_account_id')->default(1);
            $table->timestamps();

            $table->unique(array('user_id', 'lunchdate_id'));

            $table->foreign('account_id')->references('id')->on(Models::table('los_accounts'))
                ->onUpdate('restrict')->onDelete('restrict');

            $table->foreign('user_id')->references('id')->on(Models::table('los_users'))
                ->onUpdate('restrict')->onDelete('restrict');

            $table->foreign('lunchdate_id')->references('id')->on(Models::table('los_lunchdates'))
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
        Schema::dropIfExists('los_orders');
    }
}
