<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_banned')->default(false)->after('role_id');
            $table->text('banned_reason')->nullable()->after('is_banned');
        });

        Schema::table('tbl_toko', function (Blueprint $table) {
            $table->boolean('is_banned')->default(false)->after('status');
            $table->text('banned_reason')->nullable()->after('is_banned');
            $table->string('slug')->nullable()->after('nama_toko');
        });

        // Populate slug for existing toko
        $tokos = DB::table('tbl_toko')->get();
        foreach ($tokos as $toko) {
            $slug = Str::slug($toko->nama_toko);
            $count = DB::table('tbl_toko')->where('slug', $slug)->count();
            if ($count > 0) {
                $slug = $slug . '-' . $toko->id_toko;
            }
            DB::table('tbl_toko')->where('id_toko', $toko->id_toko)->update(['slug' => $slug]);
        }

        // Now make slug unique and not nullable
        Schema::table('tbl_toko', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_banned', 'banned_reason']);
        });

        Schema::table('tbl_toko', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn(['is_banned', 'banned_reason', 'slug']);
        });
    }
};
