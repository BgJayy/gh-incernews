<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('featured_image_alt')->nullable()->after('featured_image');
            $table->string('featured_image_caption')->nullable()->after('featured_image_alt');
        });

        DB::table('articles')
            ->whereNull('featured_image_alt')
            ->orWhere('featured_image_alt', '')
            ->update([
                'featured_image_alt' => DB::raw('title'),
            ]);
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['featured_image_alt', 'featured_image_caption']);
        });
    }
};
