<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Silber\Bouncer\Database\Models;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('los_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('account_id')->index();
            $table->unsignedTinyInteger('pay_method')->default(0);
            $table->unsignedInteger('credit_amt')->default(0);
            $table->unsignedInteger('fee')->default(0);
            $table->date('credit_date');
            $table->string('credit_desc', 100)->nullable();
            $table->timestamps();

            $table->foreign('account_id')->references('id')->on(Models::table('los_accounts'))
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
        Schema::dropIfExists('los_payments');
    }
}
