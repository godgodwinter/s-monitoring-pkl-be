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
        Schema::create('tagihan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('siswa_id');
            $table->string('tgl')->nullable();
            $table->string('total_tagihan')->nullable();
            //relasi
            $table->bigInteger('tapel_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('settings', function (Blueprint $table) {
            $table->bigInteger('min_pembayaran')->default(60)->nullable(); //dalam persen
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tagihan');
        Schema::table('settings', function ($table) {
            $table->dropColumn('min_pembayaran');
        });
    }
};
