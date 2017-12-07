<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
//use Silber\Bouncer\Database\Models;
use Illuminate\Support\Facades\DB;

class CreateUserAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('los_useragents', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('account_id')->index()->nullable();
            $table->string('user_agent')->nullable();
            $table->string('ipaddr')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('los_useragents');
    }
}
