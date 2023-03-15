<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outfits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player_id')->unique();
            $table->foreign('player_id')->references('id')->on('players');
            $table->unsignedBigInteger('bota_id')->nullable();
            $table->foreign('bota_id')->references('id')->on('items');
            $table->unsignedBigInteger('arma_id')->nullable();
            $table->foreign('arma_id')->references('id')->on('items');
            $table->unsignedBigInteger('armadura_id')->nullable();
            $table->foreign('armadura_id')->references('id')->on('items');
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
        Schema::dropIfExists('outfits');
    }
};
