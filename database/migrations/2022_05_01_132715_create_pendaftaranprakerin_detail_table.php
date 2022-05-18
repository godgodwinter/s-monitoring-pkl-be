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
        Schema::create('pendaftaranprakerin_detail', function (Blueprint $table) {

            //gak jadi dipakek

            $table->bigIncrements('id');
            $table->string('tempatpkl_id')->nullable();
            $table->string('status')->nullable(); //Disetujui / Ditolak / Menunggu
            $table->string('keterangan')->nullable();
            $table->string('tgl_pengajuan')->nullable();
            $table->string('tgl_konfirmasi')->nullable();
            $table->string('pendaftaranprakerin_id')->nullable();
            $table->string('pembimbinglapangan_id')->nullable();
            $table->string('pembimbingsekolah_id')->nullable();
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
        Schema::dropIfExists('pendaftaranprakerin_detail');
    }
};
