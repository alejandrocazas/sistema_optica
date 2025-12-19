<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class MigrateStockSeeder extends Seeder
{
    public function run()
    {
        $mainBranchId = 1; 
        $products = Product::all();

        foreach ($products as $product) {
            $exists = DB::table('branch_product')
                        ->where('branch_id', $mainBranchId)
                        ->where('product_id', $product->id)
                        ->exists();

            if (!$exists) {
                DB::table('branch_product')->insert([
                    'branch_id' => $mainBranchId,
                    'product_id' => $product->id,
                    'stock' => $product->stock ?? 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}