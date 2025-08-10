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
        Schema::create('sadarin_subnavigasisekre', function (Blueprint $table) {
            $table->increments('subnavigasisekre_id');
            $table->string('subnavigasisekre_nama', 255);
            $table->integer('subnavigasisekre_urutan');
            $table->string('subnavigasisekre_navigasisekre', 200);
            $table->text('subnavigasisekre_link');
            $table->integer('subnavigasisekre_status');
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
