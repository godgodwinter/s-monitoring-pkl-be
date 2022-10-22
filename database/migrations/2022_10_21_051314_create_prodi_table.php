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
        // Schema::create('prodi', function (Blueprint $table) {
        //     $table->bigIncrements('id');
        //     $table->string('nama');
        //     $table->string('kaprodi_id'); //dari table pembimbingsekolah //guru
        //     $table->softDeletes();
        //     $table->timestamps();
        // });


        Schema::create('jurusan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama');
            $table->string('kepalajurusan_id'); //dari table pembimbingsekolah //guru
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
        // Schema::dropIfExists('prodi');
        Schema::dropIfExists('jurusan');
    }
};
