<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Contracts\View\View;
use Yajra\DataTables\Facades\DataTables;

class ProductsController extends Controller
{
    public function index(): View
    {
        return view('products.index', [
            'categories' => Product::CATEGORIES,
            'suppliers' => Supplier::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Endpoint server-side processing untuk DataTables:
     * paging, sorting, dan searching dilakukan di database (bukan di browser).
     */
    public function data(Request $request): JsonResponse
    {
        $query = Product::query()
            ->with('supplier:id,name')
            ->select('products.*')
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->category))
            ->when($request->filled('supplier_id'), fn ($q) => $q->where('supplier_id', $request->supplier_id))
            ->when($request->stock_status === 'empty', fn ($q) => $q->where('stock', 0))
            ->when($request->stock_status === 'low', fn ($q) => $q->whereBetween('stock', [1, 10]));

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('supplier_name', fn (Product $p) => $p->supplier?->name ?? '-')
            ->filterColumn('supplier_name', function ($q, $keyword) {
                $q->whereHas('supplier', fn ($s) => $s->where('name', 'like', "%{$keyword}%"));
            })
            ->orderColumn('supplier_name', function ($q, $order) {
                $q->orderBy(
                    Supplier::select('name')->whereColumn('suppliers.id', 'products.supplier_id'),
                    $order
                );
            })
            ->addColumn('action', fn (Product $p) => view('products._actions', ['product' => $p])->render())
            ->rawColumns(['action'])
            ->toJson();
    }

    public function store(ProductRequest $request): JsonResponse
    {
        $product = Product::create($request->validated());

        return response()->json([
            'message' => "Produk {$product->product_name} berhasil ditambahkan.",
        ], 201);
    }

    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        $product->update($request->validated());

        return response()->json([
            'message' => "Produk {$product->product_name} berhasil diperbarui.",
        ]);
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json([
            'message' => "Produk {$product->product_name} berhasil dihapus.",
        ]);
    }
}
