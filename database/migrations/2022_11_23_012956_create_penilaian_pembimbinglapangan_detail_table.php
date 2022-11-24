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
        Schema::create('penilaian_pembimbinglapangan_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('siswa_id')->nullable();
            $table->bigInteger('penilaian_pembimbinglapangan_id')->nullable();
            $table->bigInteger('nilai')->nullable();
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
        Schema::dropIfExists('penilaian_pembimbinglapangan_detail');
    }
};
