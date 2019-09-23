<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    function getTable() {
        return config('seppay.table', 'transactions');
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->getTable(), function (Blueprint $table) {
            $table->increments('id');
            $table->integer('payable_id')->nullable();
            $table->string('payable_type')->nullable();
            /* TODO: COMPLETE THIS */
            $table->integer('amount');
            $table->integer('transId')->unique();
            $table->string('factorNumber')->nullable();
            $table->string('cardNumber')->nullable();
            $table->enum('status', ['INIT', 'SUCCESS', 'FAILED', 'FRAUD'])->default('INIT');
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
        Schema::dropIfExists($this->getTable());
    }
}
