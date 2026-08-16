<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create tbl_produk_zip
        Schema::create('tbl_produk_zip', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 255);
            $table->text('deskripsi');
            $table->decimal('harga', 10, 0);
            $table->enum('status', ['tersedia', 'masih dalam pengembangan', 'tidak tersedia']);
            $table->string('file', 255);
            $table->timestamps();
        });

        // 2. Create tbl_beli_produk
        Schema::create('tbl_beli_produk', function (Blueprint $table) {
            $table->id();
            $table->integer('qty');
            $table->enum('status', ['success', 'pending', 'deny']);
            $table->string('order_id', 255);
            $table->foreignId('produk_id')->constrained('tbl_produk_zip')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal_transaksi')->nullable();
        });

        // 3. Create tbl_pembayaran_zip
        Schema::create('tbl_pembayaran_zip', function (Blueprint $table) {
            $table->id();
            $table->decimal('total', 10, 0);
            $table->string('metode', 255);
            $table->string('order_id', 255);
        });

        // 4. Create tbl_produk_terjual
        Schema::create('tbl_produk_terjual', function (Blueprint $table) {
            $table->id();
            $table->integer('jumlah_terjual');
            $table->foreignId('produk_id')->constrained('tbl_produk_zip')->onDelete('cascade');
        });

        // 5. Create tbl_screenshots_produk
        Schema::create('tbl_screenshots_produk', function (Blueprint $table) {
            $table->id();
            $table->string('folder', 255);
            $table->foreignId('produk_id')->constrained('tbl_produk_zip')->onDelete('cascade');
        });

        // 6. Create trigger after_update_tbl_beli_produk (MySQL only)
        if (DB::getDriverName() === 'mysql') {
            DB::unprepared("
                CREATE TRIGGER `after_update_tbl_beli_produk` AFTER UPDATE ON `tbl_beli_produk` FOR EACH ROW BEGIN
                    DECLARE v_produk_id INT;
                    DECLARE v_qty INT;

                    SET v_produk_id = OLD.produk_id;
                    SET v_qty = OLD.qty;

                    IF OLD.status = 'pending' AND NEW.status = 'success' THEN
                        INSERT INTO tbl_produk_terjual (jumlah_terjual, produk_id) VALUES (v_qty, v_produk_id);
                    END IF;
                END
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS `after_update_tbl_beli_produk`");
        Schema::dropIfExists('tbl_screenshots_produk');
        Schema::dropIfExists('tbl_produk_terjual');
        Schema::dropIfExists('tbl_pembayaran_zip');
        Schema::dropIfExists('tbl_beli_produk');
        Schema::dropIfExists('tbl_produk_zip');
    }
};
