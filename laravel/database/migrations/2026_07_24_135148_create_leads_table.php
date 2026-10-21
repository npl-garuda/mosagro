<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('price_request');
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->string('inn')->nullable();
            $table->string('city')->nullable();
            $table->text('comment')->nullable();
            $table->string('contact_preference')->nullable();
            $table->string('status')->default('new');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('leads');
    }
};
