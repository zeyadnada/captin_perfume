<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variant_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')->unique()->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(0);
            $table->unsignedInteger('reserved_quantity')->default(0);
            $table->unsignedInteger('low_stock_threshold')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variant_stocks');
    }
};
