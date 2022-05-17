<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        // oneTOMANY
        Schema::create('pendaftaranprakerin_pengajuansiswa', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tempatpkl_id')->nullable(); //BelongsTo
            $table->string('pendaftaranprakerin_id')->nullable(); //BelongsTo
            $table->string('status')->nullable(); //Ditolak/Disetujui/null
            $table->string('ket')->nullable(); //keterangan
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
        Schema::dropIfExists('pendaftaranprakerin_pengajuansiswa');
    }
};
