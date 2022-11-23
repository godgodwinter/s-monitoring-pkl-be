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
            $table->integer('penilaian_guru')->default(0); //1-100 //berapa persen
            $table->integer('penilaian_pembimbing_lapangan')->default(0); //1-100 //berapa persen
            $table->integer('absensi')->default(0);
            $table->bigInteger('tapel_id');
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
        Schema::dropIfExists('penilaian');
    }
};
