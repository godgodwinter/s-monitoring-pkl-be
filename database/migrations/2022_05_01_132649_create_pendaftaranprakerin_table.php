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
        Schema::create('pendaftaranprakerin', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('siswa_id')->nullable();
            $table->string('tgl_daftar')->nullable();
            $table->string('status')->nullable(); //Belum Daftar/ Proses Daftar / Sedang Prakerin / Telah Selesai
            $table->string('siswa_id')->nullable();
            $table->string('tapel_id')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('pendaftaranprakerin');
    }
};
