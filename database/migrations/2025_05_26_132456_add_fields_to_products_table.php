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
        Schema::table('products', function (Blueprint $table) {
            // Financial details
            $table->decimal('price', 10, 2)->after('description');
            $table->decimal('compare_at_price', 10, 2)->nullable()->after('price');
            $table->decimal('cost_price', 10, 2)->nullable()->after('compare_at_price');

            // Inventory and identification
            $table->string('sku')->unique()->nullable()->after('cost_price');
            $table->integer('quantity')->default(0)->after('sku');

            // Product status and visibility
            $table->string('status')->default('draft')->after('quantity');
            $table->boolean('is_featured')->default(false)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop columns in reverse order of addition
            $table->dropColumn([
                'is_featured',
                'status',
                'quantity',
                'sku',
                'cost_price',
                'compare_at_price',
                'price',
            ]);
        });
    }
};
