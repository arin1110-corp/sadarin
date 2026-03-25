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
        Schema::create('sadarin_timkerja_detail', function (Blueprint $table) {
            $table->id('timkerja_detail_id');
            $table->unsignedBigInteger('timkerja_detail_timkerja');
            $table->unsignedBigInteger('timkerja_detail_anggota');
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
        //
        Schema::dropIfExists('sadarin_timkerja_detail');
    }
};