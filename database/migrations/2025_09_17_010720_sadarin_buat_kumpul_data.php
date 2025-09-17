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
        Schema::create('sadarin_pengumpulanberkas', function (Blueprint $table) {
            $table->increments('kumpulan_id');
            $table->string('kumpulan_user', 100);
            $table->text('kumpulan_file');
            $table->string('kumpulan_jenis', 100);
            $table->integer('kumpulan_status');
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
    }
};
