<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use \Illuminate\Database\Query\Builder;
class ProductController extends Controller
{
    public function index()
    {
        $search=request()->get('search');
        $products = Product::query()
            ->where(function ($query) use ($search) {
                /** @var $query \Illuminate\Database\Query\Builder */
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');

            })
            ->orderBy('updated_at', 'desc')
            ->paginate(8);
        return view('product.index', [
            'products' => $products
        ]);
    }

    public function view(Product $product)
    {
        return view('product.view', ['product' => $product]);
    }
}
