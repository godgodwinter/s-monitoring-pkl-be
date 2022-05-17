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
        Schema::create('tempatpkl', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama');
            $table->text('alamat')->nullable();
            $table->string('telp')->nullable();
            $table->string('penanggungjawab')->nullable();
            $table->string('nama_pimpinan')->nullable();
            $table->integer('kuota')->default(1);
            $table->string('tapel_id')->nullable();
            $table->string('status')->nullable()->default('Tersedia'); //Tidak Tersedia/Tersedia
            $table->string('tgl_mulai')->nullable(); //mulai pkl
            $table->string('tgl_selesai')->nullable(); // selesai pkl
            // $table->string('pembimbinglapangan_id')->nullable();
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
        Schema::dropIfExists('tempatpkl');
    }
};
