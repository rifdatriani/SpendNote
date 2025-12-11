<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dana_darurats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('bulan');
            $table->integer('minggu')->default(1);
            $table->string('status')->default('Pemasukan'); // atau Pengeluaran
            $table->string('sumber')->nullable();
            $table->bigInteger('jumlah')->default(0);
            $table->bigInteger('total')->default(0); // akumulasi per user
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('dana_darurats');
    }
};
