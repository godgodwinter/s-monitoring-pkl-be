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
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->string('siswa_id')->nullable(); //BelongsTo
            $table->string('label')->nullable(); //hadir / tidak hadir
            $table->string('keterangan')->nullable(); //alasan tidak hadir / kegiatan yang dilakukan
            $table->string('bukti')->nullable(); //file .jpg/.png (bukti alasan tidak hadir misal sakit berarti surat dokter ))
            $table->string('status')->nullable()->default('disetujui'); //disetujui / ditolak / menunggu konfirmasi
            $table->text('alasan')->nullable(); //alasan ditolak (optional)
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
        Schema::dropIfExists('absensi');
    }
};
