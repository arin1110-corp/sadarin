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
        Schema::create('sadarin_admin', function (Blueprint $table) {
            $table->increments('admin_id');
            $table->string('admin_nip', 100);
            $table->string('admin_role', 100);
            $table->string('admin_password', 255);
            $table->integer('admin_status'); // 0 = tidak aktif, 1 = aktif
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