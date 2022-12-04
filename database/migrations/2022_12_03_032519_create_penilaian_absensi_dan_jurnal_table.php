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
        Schema::create('penilaian_absensi_dan_jurnal', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('tapel_id')->nullable();
            $table->bigInteger('siswa_id')->nullable();
            $table->string('prefix')->nullable(); //absensi _ jurnal
            $table->string('nilai')->nullable();
            $table->string('status')->nullable()->default('Aktif');
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
        Schema::dropIfExists('penilaian_absensi');
    }
};
