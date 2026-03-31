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
        Schema::create('sadarin_tombolberkas', function (Blueprint $table) {
            $table->id('tombol_id');
            $table->string('tombol_nama')->unique();
            $table->string('tombol_prefix')->unique();
            $table->unsignedBigInteger('tombol_json');
            $table->date('tombol_expired');
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
        Schema::dropIfExists('sadarin_tombolberkas');
    }
};