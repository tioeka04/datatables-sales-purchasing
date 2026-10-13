<button type="button" class="btn btn-sm btn-outline-primary btn-edit"
        data-product="{{ json_encode($product->only(['id', 'product_code', 'product_name', 'category', 'supplier_id', 'purchase_price', 'price', 'stock', 'description'])) }}"
        title="Edit">
    <i class="bi bi-pencil-square"></i>
</button>
<button type="button" class="btn btn-sm btn-outline-danger btn-delete"
        data-id="{{ $product->id }}" data-name="{{ $product->product_name }}" title="Hapus">
    <i class="bi bi-trash"></i>
</button>
