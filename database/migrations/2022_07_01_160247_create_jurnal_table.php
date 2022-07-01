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
        Schema::create('jurnal', function (Blueprint $table) {
            $table->id();
            $table->string('siswa_id')->nullable(); //BelongsTo
            $table->string('label')->nullable(); //nama kegiatan yang dilakukan hari itu
            $table->text('desc')->nullable(); //deskripsi kegiatan yang dilakukan hari itu
            $table->string('file')->nullable(); //file bukti kegiatan jika diperlukan
            $table->string('status')->nullable()->default('disetujui'); //disetujui / ditolak / menunggu konfirmasi
            $table->text('alasan')->nullable(); //alasan ditolak (optional)
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
        Schema::dropIfExists('jurnal');
    }
};
