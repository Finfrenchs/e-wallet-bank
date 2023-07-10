<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaction_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->enum('action', ['cr', 'dr']); //enum di gunakan untuk membuat colom action yang di dalam nya cuma ada 2 (cr ini dimana kita bisa menambahkan amount /balance di akun kita. sedangkan dr adalah untuk debit)
            $table->string('thunbnail')->nullable();
            $table->softDeletes(); //digunakan untuk ketika kita inigin menghapus data dari laravel maka data tsb tdk di hapus
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_types');
    }
};
