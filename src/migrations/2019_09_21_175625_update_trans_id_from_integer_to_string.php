<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTransIdFromIntegerToString extends Migration
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
        Schema::table($this->getTable(), function (Blueprint $table) {
            $table->dropColumn('transId');
            $table->string('transId')->after('amount')->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->getTable(), function (Blueprint $table) {
            $table->dropColumn('transId');
            $table->integer('transId')->after('amount')->unique();
        });
    }
}
