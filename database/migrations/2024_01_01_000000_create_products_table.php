<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('brand')->nullable();
            $table->string('gender');
            $table->string('condition');
            $table->decimal('price', 10, 2);
            $table->string('currency', 3)->default('EUR');
            $table->string('size')->nullable();
            $table->boolean('is_sold')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
