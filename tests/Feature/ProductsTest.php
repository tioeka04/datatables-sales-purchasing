<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'product_code' => 'PRD-TEST',
            'product_name' => 'Produk Test',
            'category' => 'Elektronik',
            'supplier_id' => Supplier::factory()->create()->id,
            'purchase_price' => 1000,
            'price' => 1500,
            'stock' => 10,
            'description' => null,
        ], $overrides);
    }

    public function test_index_page_renders(): void
    {
        $this->get('/products')->assertOk()->assertSee('Master Produk');
    }

    public function test_data_endpoint_returns_server_side_json(): void
    {
        Product::factory()->count(15)->create();

        $this->getJson('/products/data?draw=1&start=0&length=10')
            ->assertOk()
            ->assertJsonPath('recordsTotal', 15)
            ->assertJsonCount(10, 'data');
    }

    public function test_data_endpoint_searches_and_filters(): void
    {
        Product::factory()->create(['product_code' => 'AAA-1', 'product_name' => 'Laptop Gaming', 'category' => 'Elektronik']);
        Product::factory()->create(['product_code' => 'BBB-1', 'product_name' => 'Meja Kayu', 'category' => 'Perabot']);

        $columns = http_build_query(['columns' => [
            ['data' => 'product_name', 'name' => 'product_name', 'searchable' => 'true', 'orderable' => 'true'],
        ]]);

        $this->getJson('/products/data?draw=1&start=0&length=10&search[value]=Laptop&'.$columns)
            ->assertJsonPath('recordsFiltered', 1);

        $this->getJson('/products/data?draw=1&start=0&length=10&category=Perabot')
            ->assertJsonPath('recordsFiltered', 1);
    }

    public function test_can_create_product(): void
    {
        $this->postJson('/products', $this->payload())->assertCreated();

        $this->assertDatabaseHas('products', ['product_code' => 'PRD-TEST']);
    }

    public function test_create_validates_input(): void
    {
        $this->postJson('/products', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['product_code', 'product_name', 'category', 'price', 'stock']);
    }

    public function test_selling_price_cannot_be_below_purchase_price(): void
    {
        $this->postJson('/products', $this->payload(['price' => 500]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['price']);
    }

    public function test_can_update_product_and_keep_its_own_code(): void
    {
        $product = Product::factory()->create(['product_code' => 'PRD-OLD']);

        $this->putJson("/products/{$product->id}", $this->payload([
            'product_code' => 'PRD-OLD',
            'product_name' => 'Nama Baru',
        ]))->assertOk();

        $this->assertDatabaseHas('products', ['id' => $product->id, 'product_name' => 'Nama Baru']);
    }

    public function test_code_must_be_unique_on_update(): void
    {
        Product::factory()->create(['product_code' => 'TAKEN']);
        $product = Product::factory()->create(['product_code' => 'MINE']);

        $this->putJson("/products/{$product->id}", $this->payload(['product_code' => 'TAKEN']))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['product_code']);
    }

    public function test_can_delete_product(): void
    {
        $product = Product::factory()->create();

        $this->deleteJson("/products/{$product->id}")->assertOk();

        $this->assertModelMissing($product);
    }
}
