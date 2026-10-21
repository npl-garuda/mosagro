<?php
namespace App\Http\Controllers;
use Illuminate\View\View;
class PageController extends Controller
{
    public function about(): View
    {
        return view('pages.about');
    }
    public function contacts(): View
    {
        return view('pages.contacts');
    }
    public function privacy(): View
    {
        return view('pages.privacy');
    }
    public function consent(): View
    {
        return view('pages.consent');
    }
    public function requisites(): View
    {
        return view('pages.requisites');
    }
}
