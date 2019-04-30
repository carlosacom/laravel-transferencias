<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopUpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('top_ups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reseller_id', false, true);
            $table->integer('user_id', false, true);
            $table->decimal('amount', 15, 4);
            $table->decimal('bonus', 15, 4);
            $table->string('observation', 255);
            $table->timestamps();
            $table->foreign('reseller_id')->references('id')->on('resellers');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('top_ups');
    }
}
