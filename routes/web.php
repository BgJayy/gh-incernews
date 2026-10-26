<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/cari', [SearchController::class, 'index'])->name('search');
Route::get('/robots.txt', [RobotsController::class, 'index'])->name('robots');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/kategori/{slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/{slug}', [ArticleController::class, 'show'])->name('articles.show');
