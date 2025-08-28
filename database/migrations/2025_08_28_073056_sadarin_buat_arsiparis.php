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
        Schema::create('sadarin_asiparis', function (Blueprint $table) {
            $table->increments('asiparis_id');
            $table->string('asiparis_user', 100);
            $table->string('asiparis_nama', 100);
            $table->string('asiparis_noberkas', 150);
            $table->string('asiparis_noitemarsip', 150);
            $table->string('asiparis_kodeklasifikasi', 255);
            $table->string('asiparis_uraian', 255);
            $table->date('asiparis_tanggal');
            $table->string('asiparis_tahun', 20);
            $table->integer('asiparis_volume');
            $table->string('asiparis_satuan', 100);
            $table->string('asiparis_tingkatperkembangan', 255);
            $table->string('asiparis_lokasipenyimpanan', 255);
            $table->string('asiparis_klasifikasikeamanan', 255);
            $table->string('asiparis_aksesarsip', 100);
            $table->integer('asiparis_aktif');
            $table->integer('asiparis_inaktif');
            $table->integer('asiparis_musnah');
            $table->integer('asiparis_keterangan');
            $table->integer('asiparis_status');
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
