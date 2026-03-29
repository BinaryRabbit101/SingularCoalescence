<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class ProductsController extends Controller
{
    public function index(): Response
    {
        $products = Product::where('published', true)
            ->orderBy('sort_order')
            ->get();

        return Inertia::render('Products/Index', [
            'products' => $products,
        ]);
    }
}
