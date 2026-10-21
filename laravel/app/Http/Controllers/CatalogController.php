<?php
namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
class CatalogController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('sort')
            ->orderBy('name')
            ->get();
        $products = Product::query()
            ->with(['brand', 'categories'])
            ->where('is_active', true)
            ->orderBy('sort')
            ->orderBy('name')
            ->paginate(24);
        return view('catalog.index', compact('categories', 'products'));
    }
    public function category(string $category): View
    {
        $categoryModel = Category::query()
            ->where('slug', $category)
            ->where('is_active', true)
            ->firstOrFail();
        $products = $categoryModel->products()
            ->with(['brand', 'categories'])
            ->where('is_active', true)
            ->orderBy('products.sort')
            ->orderBy('products.name')
            ->paginate(24);
        return view('catalog.category', [
            'category' => $categoryModel,
            'products' => $products,
        ]);
    }
    public function product(string $category, string $product): View|RedirectResponse
    {
        $categoryModel = Category::query()
            ->where('slug', $category)
            ->where('is_active', true)
            ->firstOrFail();
        $productModel = Product::query()
            ->with(['brand', 'packagings', 'documents', 'categories'])
            ->where('slug', $product)
            ->where('is_active', true)
            ->firstOrFail();
        $primary = $productModel->categories->firstWhere('pivot.is_primary', true)
            ?? $productModel->categories->first();
        if ($primary && $primary->slug !== $categoryModel->slug) {
            return redirect()->route('catalog.product', [
                'category' => $primary->slug,
                'product' => $productModel->slug,
            ], 301);
        }
        return view('catalog.product', [
            'category' => $categoryModel,
            'product' => $productModel,
        ]);
    }
}
