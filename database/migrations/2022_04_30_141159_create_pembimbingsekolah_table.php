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
        Schema::create('pembimbingsekolah', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama');
            $table->string('email')->nullable();
            $table->string('username')->nullable();
            $table->string('nomeridentitas')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->string('agama')->nullable();
            $table->string('tempatlahir')->nullable();
            $table->string('tgllahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('jk')->nullable();
            $table->string('telp')->nullable();
            $table->string('status_login')->nullable()->default('Aktif'); //Aktif/Nonaktif login
            $table->string('status_data')->nullable()->default('Aktif'); //Aktif/Nonaktif Sembunyikan atau sudah tidak digunakan pada tapel saat ini
            // $table->string('tapel_id')->nullable();
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
        Schema::dropIfExists('pembimbingsekolah');
    }
};
