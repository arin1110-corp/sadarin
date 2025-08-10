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
        Schema::create('sadarin_navigasi_sekretariat', function (Blueprint $table) {
            $table->increments('navigasisekre_id');
            $table->string('navigasisekre_nama', 100);
            $table->string('navigasisekre_deskripsi', 255);
            $table->integer('navigasisekre_urutan');
            $table->string('navigasisekre_subbag', 100);
            $table->integer('navigasisekre_level');
            $table->boolean('navigasisekre_status');
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
