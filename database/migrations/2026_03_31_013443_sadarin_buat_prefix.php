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
        Schema::create('sadarin_mappingtombol', function (Blueprint $table) {
            $table->id('mapping_id');
            $table->unsignedBigInteger('mapping_tombol');
            $table->string('mapping_jeniskerja');
            $table->string('mapping_folderid');
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
        Schema::dropIfExists('sadarin_mappingtombol');
    }
};