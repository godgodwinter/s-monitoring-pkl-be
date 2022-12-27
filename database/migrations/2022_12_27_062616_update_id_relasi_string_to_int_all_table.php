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
        Schema::table('kelasdetail', function (Blueprint $table) {
            $table->bigInteger('siswa_id')->change();
            $table->bigInteger('kelas_id')->change();
        });
        Schema::table('kelas', function (Blueprint $table) {
            $table->bigInteger('tapel_id')->change();
        });
        Schema::table('jurusan', function (Blueprint $table) {
            $table->bigInteger('tapel_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('kelasdetail', function (Blueprint $table) {
        //     $table->bigInteger('siswa_id')->change();
        //     $table->bigInteger('kelas_id')->change();
        // });
    }
};
