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
        Schema::create('sadarin_ubahuser', function (Blueprint $table) {
            $table->increments('ubahuser_id');
            $table->string('ubahuser_iduser', 100);
            $table->string('ubahuser_nip', 100);
            $table->string('ubahuser_nama', 100);
            $table->string('ubahuser_nik', 100);
            $table->date('ubahuser_tgllahir');
            $table->string('ubahuser_jabatan', 100);
            $table->string('ubahuser_npwp', 100);
            $table->string('ubahuser_pendidikan', 100);
            $table->string('ubahuser_norek', 150);
            $table->date('ubahuser_tmt', 100);
            $table->date('ubahuser_spmt', 100);
            $table->string('ubahuser_gelardepan', 100);
            $table->string('ubahuser_gelarbelakang', 100);
            $table->string('ubahuser_kelasjabatan', 100);
            $table->string('ubahuser_eselon', 100);
            $table->string('ubahuser_golongan', 100);
            $table->string('ubahuser_email', 100);
            $table->string('ubahuser_notelp', 100);
            $table->string('ubahuser_alamat', 255);
            $table->string('ubahuser_jk', 1);
            $table->text('ubahuser_foto');
            $table->string('ubahuser_bidang', 100);
            $table->string('ubahuser_jmltanggungan', 100);
            $table->integer('ubahuser_status');
            $table->string('ubahuser_jeniskerja', 100);
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
