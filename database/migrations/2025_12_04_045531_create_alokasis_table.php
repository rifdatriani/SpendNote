<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('alokasis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('nama_alokasi'); // kategori / nama
            $table->string('subkategori')->nullable();
            $table->enum('tipe', ['pemasukan','pengeluaran'])->default('pengeluaran');
            $table->bigInteger('jumlah');
            $table->date('tanggal')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('alokasis');
    }
};
