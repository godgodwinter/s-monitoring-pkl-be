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
        Schema::create('penilaian_guru', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('penilaian_id');
            $table->string('nama')->nullable();
            $table->string('status')->nullable()->default('Aktif');
            $table->bigInteger('jurusan_id');
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
        Schema::dropIfExists('penilaian_guru');
    }
};
