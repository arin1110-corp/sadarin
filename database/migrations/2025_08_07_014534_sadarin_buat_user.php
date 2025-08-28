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
        Schema::create('sadarin_user', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('user_nip', 100);
            $table->string('user_nama', 100);
            $table->string('user_nik', 100);
            $table->date('user_tgllahir');
            $table->string('user_jabatan', 100);
            $table->string('user_npwp', 100)->default('-');
            $table->string('user_pendidikan', 100)->default('-');
            $table->string('user_norek', 150)->default('-');
            $table->date('user_tmt', 100)->default('2025-01-01');
            $table->date('user_spmt', 100)->default('2025-01-01');
            $table->string('user_gelardepan', 100)->default('-');
            $table->string('user_gelarbelakang', 100)->default('-');
            $table->string('user_kelasjabatan', 100);
            $table->string('user_eselon', 100);
            $table->string('user_golongan', 100);
            $table->string('user_email', 100);
            $table->string('user_notelp', 100);
            $table->string('user_alamat', 255);
            $table->string('user_jk', 1);
            $table->text('user_foto');
            $table->string('user_bidang', 100);
            $table->string('user_jmltanggungan', 100);
            $table->integer('user_status');
            $table->string('user_jeniskerja', 100);
            $table->integer('user_timkerja')->default(0);
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
