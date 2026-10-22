<?php
namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\View\View;
class HomeController extends Controller
{
    public function index(): View
    {
        return view('home.index', [
            'categories' => Category::query()->activeOrdered()->get(['id', 'slug', 'name']),
        ]);
    }
}
