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
        Schema::create('rice_by_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rice_type_id');
            $table->foreign('rice_type_id')->references('id')->on('rice_types');
            // $table->date('date');
            $table->year('year');
            $table->char('month');
            $table->integer('price');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('rice_by_prices');
    }
};
