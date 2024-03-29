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
            $table->bigInteger('pembimbinglapangan_id')->nullable();
            $table->bigInteger('pembimbingsekolah_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pendaftaranprakerin_proses', function ($table) {
            $table->dropColumn('pembimbinglapangan_id');
            $table->dropColumn('pembimbingsekolah_id');
        });
    }
};
