<?php
namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\View\View;
class ExclusiveController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->with(['brand', 'categories'])
            ->where('is_active', true)
            ->where('is_exclusive', true)
            ->orderBy('sort')
            ->orderBy('name')
            ->get();
        return view('exclusive.index', compact('products'));
    }
}
