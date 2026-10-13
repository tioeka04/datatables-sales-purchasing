<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /** Jumlah data dummy; sengaja banyak supaya server-side processing terasa manfaatnya. */
    private const TOTAL = 1000;

    public function run(): void
    {
        $supplierIds = Supplier::pluck('id')->all();
        $now = now();

        $rows = Product::factory()
            ->count(self::TOTAL)
            ->sequence(fn (Sequence $s) => [
                'product_code' => sprintf('PRD-%05d', $s->index + 1),
                'supplier_id' => $supplierIds[array_rand($supplierIds)],
            ])
            ->make()
            ->map(fn (Product $p) => $p->getAttributes() + ['created_at' => $now, 'updated_at' => $now])
            ->all();

        DB::transaction(function () use ($rows) {
            foreach (array_chunk($rows, 200) as $chunk) {
                Product::insert($chunk);
            }
        });
    }
}
