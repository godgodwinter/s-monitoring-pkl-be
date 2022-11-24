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
        Schema::create('penilaian', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('penilaian_guru')->nullable()->default(0); //1-100 //berapa persen
            $table->integer('penilaian_pembimbinglapangan')->nullable()->default(0); //1-100 //berapa persen
            $table->integer('absensi')->nullable()->default(0);
            $table->integer('jurnal')->nullable()->default(0);
            $table->bigInteger('tapel_id')->nullable();
            $table->string('status')->nullable()->default('Aktif');
            $table->bigInteger('jurusan_id')->nullable();
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
        Schema::dropIfExists('penilaian');
    }
};
