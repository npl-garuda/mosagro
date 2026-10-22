<?php
namespace App\Http\Controllers;
use App\Support\FloraBrand;
use Illuminate\View\View;
class ExclusiveController extends Controller
{
    public function index(): View
    {
        $products = FloraBrand::productsQuery()
            ->orderBy('sort')
            ->orderBy('name')
            ->get();
        return view('exclusive.index', compact('products'));
    }
}
