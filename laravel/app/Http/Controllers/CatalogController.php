<?php
namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;
class CatalogController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $products = Product::query()
            ->with(['brand', 'categories', 'packagings', 'documents', 'images'])
            ->where('is_active', true)
            ->when($q !== '', fn ($query) => $query->where('name', 'like', '%'.$q.'%'))
            ->orderBy('sort')
            ->orderBy('name')
            ->paginate(24)
            ->withQueryString();
        $emptyMessage = $q !== ''
            ? 'Таких товаров не найдено, проверьте правильность поиска ...'
            : 'Товары скоро появятся.';
        if ($this->wantsCatalogPartial($request)) {
            return view('catalog._results', [
                'products' => $products,
                'emptyMessage' => $emptyMessage,
            ]);
        }
        return view('catalog.index', [
            'products' => $products,
            'categories' => $this->filterCategories(),
            'activeFilter' => null,
            'searchQuery' => $q,
            'emptyMessage' => $emptyMessage,
        ]);
    }
    public function category(Request $request, string $category): View|RedirectResponse
    {
        if ($category === 'all') {
            return redirect()->route('catalog.index');
        }
        $q = trim((string) $request->query('q', ''));
        $categoryModel = Category::query()
            ->where('slug', $category)
            ->where('is_active', true)
            ->firstOrFail();
        $products = $categoryModel->products()
            ->with(['brand', 'categories', 'packagings', 'documents', 'images'])
            ->where('products.is_active', true)
            ->when($q !== '', fn ($query) => $query->where('products.name', 'like', '%'.$q.'%'))
            ->orderBy('products.sort')
            ->orderBy('products.name')
            ->paginate(24)
            ->withQueryString();
        $emptyMessage = $q !== ''
            ? 'Таких товаров не найдено, проверьте правильность поиска ...'
            : 'В этой категории пока нет товаров.';
        if ($this->wantsCatalogPartial($request)) {
            return view('catalog._results', [
                'products' => $products,
                'emptyMessage' => $emptyMessage,
            ]);
        }
        return view('catalog.category', [
            'category' => $categoryModel,
            'products' => $products,
            'categories' => $this->filterCategories(),
            'activeFilter' => $categoryModel->slug,
            'searchQuery' => $q,
            'emptyMessage' => $emptyMessage,
        ]);
    }
    public function product(string $category, string $product): View|RedirectResponse
    {
        $productModel = Product::query()
            ->with(['brand', 'packagings', 'documents', 'categories', 'images'])
            ->where('slug', $product)
            ->where('is_active', true)
            ->firstOrFail();
        $primary = $productModel->categories->firstWhere('pivot.is_primary', true)
            ?? $productModel->categories->first();
        if ($category === 'all') {
            if ($primary) {
                return redirect()->route('catalog.product', [
                    'category' => $primary->slug,
                    'product' => $productModel->slug,
                ], 301);
            }
            $categoryModel = (object) [
                'slug' => 'all',
                'name' => 'Все товары',
            ];
            $related = $this->relatedProducts($productModel, null);
            return view('catalog.product', [
                'category' => $categoryModel,
                'product' => $productModel,
                'related' => $related,
            ]);
        }
        $categoryModel = Category::query()
            ->where('slug', $category)
            ->where('is_active', true)
            ->firstOrFail();
        if ($primary && $primary->slug !== $categoryModel->slug) {
            return redirect()->route('catalog.product', [
                'category' => $primary->slug,
                'product' => $productModel->slug,
            ], 301);
        }
        if (! $primary) {
            return redirect()->route('catalog.product', [
                'category' => 'all',
                'product' => $productModel->slug,
            ], 301);
        }
        $related = $this->relatedProducts($productModel, $categoryModel->id);
        return view('catalog.product', [
            'category' => $categoryModel,
            'product' => $productModel,
            'related' => $related,
        ]);
    }
    private function relatedProducts(Product $productModel, ?int $categoryId): Collection
    {
        $related = Product::query()
            ->with(['brand', 'categories', 'images'])
            ->where('is_active', true)
            ->where('id', '!=', $productModel->id)
            ->when(
                $categoryId,
                fn ($query) => $query->whereHas('categories', fn ($q) => $q->where('categories.id', $categoryId))
            )
            ->orderBy('sort')
            ->orderBy('name')
            ->limit(8)
            ->get();
        if ($related->count() < 4) {
            $extra = Product::query()
                ->with(['brand', 'categories', 'images'])
                ->where('is_active', true)
                ->where('id', '!=', $productModel->id)
                ->whereNotIn('id', $related->pluck('id'))
                ->orderBy('sort')
                ->orderBy('name')
                ->limit(8 - $related->count())
                ->get();
            $related = $related->concat($extra)->values();
        }
        return $related;
    }
    private function wantsCatalogPartial(Request $request): bool
    {
        return $request->ajax()
            || $request->headers->get('X-Requested-With') === 'XMLHttpRequest';
    }
    private function filterCategories(): Collection
    {
        return Category::query()
            ->activeOrdered()
            ->withCount([
                'products as products_count' => fn ($query) => $query->where('products.is_active', true),
            ])
            ->get(['id', 'slug', 'name']);
    }
}
