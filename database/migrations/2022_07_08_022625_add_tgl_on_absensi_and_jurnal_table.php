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
        Schema::table('absensi', function (Blueprint $table) {
            $table->string('tgl')->nullable();
        });

        Schema::table('jurnal', function (Blueprint $table) {
            $table->string('tgl')->nullable();
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
            $table->dropColumn('tgl');
        });


        Schema::table('jurnal', function ($table) {
            $table->dropColumn('tgl');
        });
    }
};
