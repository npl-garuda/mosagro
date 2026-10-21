<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('file_path')->nullable();
            $table->string('external_url')->nullable();
            $table->foreignId('document_section_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('documents');
    }
};
