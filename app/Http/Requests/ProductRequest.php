<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_code' => [
                'required', 'string', 'max:20',
                Rule::unique('products', 'product_code')->ignore($this->route('product')),
            ],
            'product_name' => ['required', 'string', 'max:150'],
            'category' => ['required', Rule::in(Product::CATEGORIES)],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'purchase_price' => ['required', 'numeric', 'min:0', 'max:9999999999999'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999999', 'gte:purchase_price'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'product_code' => 'kode produk',
            'product_name' => 'nama produk',
            'category' => 'kategori',
            'supplier_id' => 'supplier',
            'purchase_price' => 'harga beli',
            'price' => 'harga jual',
            'stock' => 'stok',
            'description' => 'deskripsi',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':Attribute wajib diisi.',
            'unique' => ':Attribute sudah digunakan.',
            'numeric' => ':Attribute harus berupa angka.',
            'integer' => ':Attribute harus berupa bilangan bulat.',
            'in' => ':Attribute tidak valid.',
            'exists' => ':Attribute tidak ditemukan.',
            'min.numeric' => ':Attribute minimal :min.',
            'max.numeric' => ':Attribute maksimal :max.',
            'max.string' => ':Attribute maksimal :max karakter.',
            'price.gte' => 'Harga jual tidak boleh lebih kecil dari harga beli.',
        ];
    }
}
