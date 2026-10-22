<?php
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\DealerController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ExclusiveController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PriceController;
use Illuminate\Support\Facades\Route;
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contacts', [PageController::class, 'contacts'])->name('contacts');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/consent', [PageController::class, 'consent'])->name('consent');
Route::get('/requisites', [PageController::class, 'requisites'])->name('requisites');
Route::get('/dealer', [DealerController::class, 'create'])->name('dealer.create');
Route::post('/dealer', [DealerController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('dealer.store');
Route::get('/dealer/thanks', [DealerController::class, 'thanks'])->name('dealer.thanks');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{category}', [CatalogController::class, 'category'])->name('catalog.category');
Route::get('/catalog/{category}/{product}', [CatalogController::class, 'product'])->name('catalog.product');
Route::get('/exclusive', [ExclusiveController::class, 'index'])->name('exclusive');
Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
Route::get('/documents/{section}', [DocumentController::class, 'section'])->name('documents.section');
Route::get('/price', [PriceController::class, 'create'])->name('price.create');
Route::post('/price', [PriceController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('price.store');
Route::get('/price/thanks', [PriceController::class, 'thanks'])->name('price.thanks');
Route::redirect('/cart', '/price', 301);
Route::redirect('/checkout', '/price', 301);
Route::redirect('/login', '/price', 301);
Route::redirect('/cabinet', '/price', 301);
