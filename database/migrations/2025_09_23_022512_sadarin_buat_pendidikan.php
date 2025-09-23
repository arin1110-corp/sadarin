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
        Schema::create('sadarin_pendidikan', function (Blueprint $table) {
            $table->increments('pendidikan_id');
            $table->string('pendidikan_jenjang', 100);
            $table->string('pendidikan_jurusan', 100);
            $table->integer('pendidikan_status'); // 0 = tidak aktif, 1 = aktif
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
