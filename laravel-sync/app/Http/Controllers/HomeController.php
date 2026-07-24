<?php
namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\PartnerBrand;
use App\Models\Product;
use Illuminate\View\View;
class HomeController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('sort')
            ->orderBy('name')
            ->get();
        $exclusive = Product::query()
            ->with(['brand', 'categories'])
            ->where('is_active', true)
            ->where('is_exclusive', true)
            ->orderBy('sort')
            ->limit(6)
            ->get();
        $newProducts = Product::query()
            ->with(['brand', 'categories'])
            ->where('is_active', true)
            ->where('is_new', true)
            ->orderBy('sort')
            ->limit(6)
            ->get();
        $partners = PartnerBrand::query()
            ->where('is_active', true)
            ->orderBy('sort')
            ->orderBy('name')
            ->get();
        return view('home.index', compact('categories', 'exclusive', 'newProducts', 'partners'));
    }
}
