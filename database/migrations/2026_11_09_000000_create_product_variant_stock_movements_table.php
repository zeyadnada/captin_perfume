<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variant_stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_stock_id')->constrained()->restrictOnDelete();
            $table->string('type', 3);
            $table->unsignedInteger('quantity');
            $table->text('reason');
            $table->unsignedInteger('balance_after');
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->timestamps();

            $table->index(['reference_type', 'reference_id'], 'stock_movements_reference_index');
            $table->index(['product_variant_stock_id', 'created_at'], 'stock_movements_stock_created_index');
        });

        $timestamp = now();

        DB::table('product_variant_stock_movements')->insertUsing(
            [
                'product_variant_stock_id',
                'type',
                'quantity',
                'reason',
                'balance_after',
                'reference_type',
                'reference_id',
                'created_at',
                'updated_at',
            ],
            DB::table('product_variant_stocks')
                ->where('quantity', '>', 0)
                ->select('id as product_variant_stock_id')
                ->selectRaw("'in' as type")
                ->addSelect('quantity')
                ->selectRaw("'Opening balance before movement tracking' as reason")
                ->addSelect('quantity as balance_after')
                ->selectRaw("'opening_balance' as reference_type")
                ->selectRaw('NULL as reference_id')
                ->selectRaw('? as created_at', [$timestamp])
                ->selectRaw('? as updated_at', [$timestamp])
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variant_stock_movements');
    }
};
