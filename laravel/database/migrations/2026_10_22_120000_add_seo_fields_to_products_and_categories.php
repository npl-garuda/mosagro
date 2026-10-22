<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('seo_title')->nullable()->after('sort');
            $table->string('seo_description', 512)->nullable()->after('seo_title');
            $table->string('seo_h1')->nullable()->after('seo_description');
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->string('seo_title')->nullable()->after('sort');
            $table->string('seo_description', 512)->nullable()->after('seo_title');
            $table->string('seo_h1')->nullable()->after('seo_description');
        });
        DB::table('categories')->where('slug', 'floora')->update([
            'seo_title' => 'HB-101 (Floora) оптом | МосАгро',
            'seo_h1' => 'HB-101 (Floora)',
            'seo_description' => 'HB-101 и препараты Floora оптом от МосАгро. Фасовки и документы в карточках, цены - в прайсе.',
        ]);
        DB::table('products')->where('slug', 'kornevin')->update([
            'seo_title' => 'Корневин оптом - для рассады и черенков | МосАгро',
            'seo_h1' => 'Корневин',
        ]);
        DB::table('products')->where('slug', 'floora')->update([
            'seo_title' => 'HB-101 оптом | МосАгро',
            'seo_h1' => 'HB-101',
        ]);
    }
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['seo_title', 'seo_description', 'seo_h1']);
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['seo_title', 'seo_description', 'seo_h1']);
        });
    }
};
