<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('catatans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');

            $table->string('kategori');       // contoh: Belanja, Makan, Transport
            $table->string('subkategori')->nullable(); 
            $table->enum('tipe', ['pemasukan', 'pengeluaran']);
            $table->bigInteger('nominal');
            $table->date('tanggal');
            $table->text('keterangan')->nullable();

            $table->timestamps();

            // relasi user
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('catatans');
    }
};
