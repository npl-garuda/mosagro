<?php
namespace App\Http\Controllers;
use App\Models\DocumentSection;
use Illuminate\View\View;
class DocumentController extends Controller
{
    public function index(): View
    {
        $sections = DocumentSection::query()
            ->with('brand')
            ->where('is_active', true)
            ->orderBy('sort')
            ->orderBy('name')
            ->get();
        return view('documents.index', compact('sections'));
    }
    public function section(string $section): View
    {
        $sectionModel = DocumentSection::query()
            ->with([
                'brand',
                'documents' => fn ($q) => $q->with('product')->orderBy('sort')->orderBy('id'),
            ])
            ->where('slug', $section)
            ->where('is_active', true)
            ->firstOrFail();
        $documentGroups = $sectionModel->documents
            ->groupBy(fn ($document) => $document->product_id ?: 0)
            ->map(function ($docs, $productId) {
                $product = $docs->first()?->product;
                return [
                    'key' => (int) $productId,
                    'title' => $product?->name ?: 'Общие документы',
                    'product' => $product,
                    'documents' => $docs->values(),
                ];
            })
            ->sortBy(fn ($group) => [
                $group['key'] === 0 ? 1 : 0,
                mb_strtolower($group['title']),
            ])
            ->values();
        return view('documents.section', [
            'section' => $sectionModel,
            'documentGroups' => $documentGroups,
        ]);
    }
}
