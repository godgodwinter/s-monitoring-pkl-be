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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_nama')->default('SI App Nama')->nullable();
            $table->string('app_namapendek')->default('SIM')->nullable();
            $table->string('paginationjml')->default('10')->nullable();
            $table->string('pendaftaranpkl')->default('Aktif')->nullable();
            $table->string('login_siswa')->default('Aktif')->nullable();
            $table->string('login_pembimbingsekolah')->default('Aktif')->nullable();
            $table->string('login_pembimbinglapangan')->default('Aktif')->nullable();
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
        Schema::dropIfExists('settings');
    }
};
