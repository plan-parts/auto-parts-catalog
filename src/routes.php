<?php

use Illuminate\Support\Facades\Route;
use PlanParts\Catalog\Http\Controllers\CatalogController;

Route::group([
    'prefix'     => config('auto-parts-catalog.route_prefix', 'auto-parts'),
    'middleware' => config('auto-parts-catalog.middleware', ['web']),
    'as'         => 'auto-parts.',
], function () {
    Route::get('/',                                  [CatalogController::class, 'index'])->name('index');
    Route::get('/search',                            [CatalogController::class, 'search'])->name('search');
    Route::get('/suggest',                           [CatalogController::class, 'suggest'])->name('suggest');
    Route::get('/brands/{mfaId}/models',             [CatalogController::class, 'models'])->name('models');
    Route::get('/models/{msId}/modifications',       [CatalogController::class, 'modifications'])->name('modifications');
    Route::get('/vehicle/{ktype}/categories',        [CatalogController::class, 'categories'])->name('categories');
    Route::get('/vehicle/{ktype}/category/{ptId}',   [CatalogController::class, 'parts'])->name('parts');
    Route::get('/article/{artId}',                   [CatalogController::class, 'article'])->name('article');
});
