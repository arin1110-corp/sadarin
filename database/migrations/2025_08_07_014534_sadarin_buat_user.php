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
            $table->string('user_tgllahir', 100);
            $table->string('user_jabatan', 100);
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
