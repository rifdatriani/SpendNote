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

            // yang kamu butuhkan
            $table->date('tanggal');      // tanggal transaksi
            $table->string('status');     // pemasukan / pengeluaran
            $table->bigInteger('nominal'); // jumlah transaksi
            $table->bigInteger('total');   // total akumulasi per transaksi (opsional)

            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('dana_darurats');
    }
};
