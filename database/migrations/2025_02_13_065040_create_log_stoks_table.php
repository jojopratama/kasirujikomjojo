<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

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
            $table->unsignedBigInteger('Users_Id');
            $table->timestamps();
        });

        DB::unprepared('
            Create Trigger log_stok
            After Update on produks
            For Each Row
            Begin
                INSERT INTO log_stoks (ProdukId, JumlahProduk, Users_Id, created_at) 
        VALUES (NEW.ProdukId, NEW.Stok - OLD.Stok, NEW.Users_Id, "' . Carbon::now('Asia/Jakarta') . '");
    END;
');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_stoks');
        DB::unprepared('DROP TRIGGER IF EXISTS log_stok');
    }
};