<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $categories = \App\Models\Category::all();
        return view('catalog', compact('products', 'categories'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        // Related products: items from same category or random
        $relatedProducts = Product::where('id', '!=', $id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
