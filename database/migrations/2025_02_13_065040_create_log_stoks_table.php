<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('log_stoks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ProdukId');
            $table->integer('JumlahProduk');
            $table->unsignedBigInteger('UsersId');
            $table->timestamps();
        });

        DB::unprepared('
        CREATE TRIGGER log_stok 
        AFTER UPDATE ON produks 
        FOR EACH ROW
        BEGIN 
            INSERT INTO log_stoks (ProdukId, JumlahProduk, UsersId) 
            VALUES (NEW.id, NEW.Stok - OLD.Stok, NEW.UsersId); 
        END;
    ');
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {


        Schema::dropIfExists('log_stoks');
        // Hapus trigger sebelum menghapus tabel
        DB::unprepared('
            DELIMITER $$ 
            DROP TRIGGER IF EXISTS log_stok
        ');

      
    }
};
