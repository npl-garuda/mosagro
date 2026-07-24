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
            ->with(['documents', 'brand'])
            ->where('slug', $section)
            ->where('is_active', true)
            ->firstOrFail();
        return view('documents.section', [
            'section' => $sectionModel,
        ]);
    }
}
