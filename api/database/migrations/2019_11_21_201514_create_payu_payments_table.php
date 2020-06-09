<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayuPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payu_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('local_order_id')->nullable();
            $table->string('payu_order_id');
            $table->string('currency');
            $table->float('totalAmount');
            $table->longText('history')->nullable();
            $table->string('status');
            $table->string('link', 512);
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
        Schema::dropIfExists('payu_payments');
    }
}
