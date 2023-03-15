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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->foreign('user_id')->references('id')->on('users');
            $table->enum('status', ['activo', 'inactivo'])->default('inactivo');
            $table->enum('type', ['human', 'zombie'])->default('human')->nullable();
            $table->float('life',5, 2)->default(100);
            // $table->unsignedBigInteger('bota_id')->nullable();
            // $table->foreignId('bota_id')->nullable()
            // ->onUpdate('cascade')
            // ->nullOnDelete()
            // ->default(null);
            // $table->unsignedBigInteger('arma_id')->nullable();
            // $table->foreignId('arma_id')->nullable()
            // ->onUpdate('cascade')
            // ->nullOnDelete()
            // ->default(null);
            // $table->unsignedBigInteger('armadura_id')->nullable();
            // $table->foreignId('armadura_id')->nullable()
            // ->onUpdate('cascade')
            // ->nullOnDelete()
            // ->default(null);
            // $table->foreign('last_attack_to')->references('id')->on('users');
            // $table->string('last_attack_type');
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
        Schema::dropIfExists('players');
    }
};
