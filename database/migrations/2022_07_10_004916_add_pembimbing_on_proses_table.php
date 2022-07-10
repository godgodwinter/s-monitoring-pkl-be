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
        Schema::table('pendaftaranprakerin_proses', function (Blueprint $table) {
            $table->string('pembimbinglapangan_id')->nullable();
            $table->string('pembimbingsekolah_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('absensi', function ($table) {
            $table->dropColumn('pembimbinglapangan_id');
            $table->dropColumn('pembimbingsekolah_id');
        });
    }
};
