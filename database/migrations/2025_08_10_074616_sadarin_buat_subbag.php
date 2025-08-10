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
        //
        Schema::create('sadarin_subbag', function (Blueprint $table) {
            $table->increments('subbag_id');
            $table->string('subbag_nama', 100);
            $table->string('subbag_bidang', 100);
            $table->string('subbag_link', 100);
            $table->integer('subbag_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
