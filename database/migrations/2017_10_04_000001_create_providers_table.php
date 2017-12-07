<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('los_providers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('provider_name', 50)->unique();
            $table->string('provider_image', 50);
            $table->string('provider_url');
            $table->string('provider_includes')->nullable();
            $table->boolean('allow_orders')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('los_providers');
    }
}
