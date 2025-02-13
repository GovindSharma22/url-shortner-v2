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
         if (!Schema::hasColumn('short_urls', 'hit_count')) {
        Schema::table('short_urls', function (Blueprint $table) {
            $table->integer('hit_count')->default(0)->after('short_code');
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('short_urls', function (Blueprint $table) {
        $table->dropColumn('hit_count');
    });
    }
};
