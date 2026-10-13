<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            ['SUP-0001', 'PT Maju Jaya Elektronik', '081234567801'],
            ['SUP-0002', 'PT Sinar Alat Tulis', '081234567802'],
            ['SUP-0003', 'PT Nusantara Furnitur', '081234567803'],
            ['SUP-0004', 'PT Sumber Pangan Sejahtera', '081234567804'],
            ['SUP-0005', 'PT Otomotif Prima', '081234567805'],
        ];

        foreach ($suppliers as [$code, $name, $phone]) {
            Supplier::updateOrCreate(
                ['supplier_code' => $code],
                ['name' => $name, 'phone' => $phone],
            );
        }
    }
}
