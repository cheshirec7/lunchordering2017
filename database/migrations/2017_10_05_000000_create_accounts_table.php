<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('los_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account_name');
            $table->string('email')->unique();
            $table->string('fb_id')->nullable()->unique();
            $table->string('password');
            $table->boolean('active')->default(false);
            $table->boolean('allow_new_orders')->default(true);
            $table->unsignedInteger('confirmed_credits')->default(0);
            $table->unsignedInteger('confirmed_debits')->default(0);
            $table->unsignedInteger('total_debits')->default(0);
            $table->unsignedInteger('fees')->default(0);
            $table->unsignedInteger('total_orders')->default(0);
            $table->rememberToken();
            $table->timestamps();
//            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('los_accounts');
    }
}
