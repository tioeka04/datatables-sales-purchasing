<?php

use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/products');

Route::get('products/data', [ProductsController::class, 'data'])->name('products.data');
Route::resource('products', ProductsController::class)->except(['create', 'edit', 'show']);
