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
        Schema::create('sadarin_timkerja', function (Blueprint $table) {
            $table->id('timkerja_id');
            $table->unsignedBigInteger('timkerja_bidang');
            $table->string('timkerja_nama', 100);
            $table->unsignedBigInteger('timkerja_ketuatim');
            $table->text('timkerja_uraian');
            $table->boolean('timkerja_status')->default(true);
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
        Schema::dropIfExists('sadarin_timkerja');
    }
};