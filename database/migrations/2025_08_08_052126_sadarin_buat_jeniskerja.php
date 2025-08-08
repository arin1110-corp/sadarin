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
        Schema::create('sadarin_jeniskerja', function (Blueprint $table) {
            $table->increments('jeniskerja_id');
            $table->string('jeniskerja_nama', 100);
            $table->string('jeniskerja_singkatan', 255);
            $table->integer('jeniskerja_status');
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
