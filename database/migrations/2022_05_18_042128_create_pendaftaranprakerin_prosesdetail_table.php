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
        Schema::create('pendaftaranprakerin_prosesdetail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('siswa_id')->nullable(); //BelongsTo
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pendaftaranprakerin_prosesdetail');
    }
};
