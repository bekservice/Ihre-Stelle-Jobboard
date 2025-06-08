<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\WebhookController;

Route::get('/', [JobController::class, 'index'])->name('home');
Route::get('/jobs/{job:slug}', [JobController::class, 'show'])->name('jobs.show');
Route::get('/jobs', [JobController::class, 'search'])->name('jobs.search');

// Legal pages
Route::view('/impressum', 'legal.impressum')->name('impressum');
Route::view('/datenschutz', 'legal.datenschutz')->name('datenschutz');

// Application routes
Route::get('/jobs/{job:slug}/bewerben', [App\Http\Controllers\ApplicationController::class, 'show'])->name('application.show');
Route::post('/jobs/{job:slug}/bewerben', [App\Http\Controllers\ApplicationController::class, 'store'])->name('application.store');
Route::get('/jobs/{job:slug}/bewerbung-erfolgreich', [App\Http\Controllers\ApplicationController::class, 'success'])->name('application.success');

// Webhook routes
Route::post('/webhook/sync-job', [WebhookController::class, 'syncSingleJob'])->name('webhook.sync-job');

// Sitemap routes
Route::get('/sitemap.xml', [SitemapController::class, 'index']);
Route::get('/sitemap-jobs.xml', [SitemapController::class, 'jobs']);

// Robots.txt
Route::get('/robots.txt', function () {
    $content = "User-agent: *\nAllow: /\n\n# Sitemaps\nSitemap: " . url('/sitemap.xml') . "\n\n# Disallow admin areas\nDisallow: /admin/\nDisallow: /api/\n\n# Allow job pages for better indexing\nAllow: /jobs/\nAllow: /\n\n# Crawl-delay for respectful crawling\nCrawl-delay: 1";
    
    return response($content, 200)
        ->header('Content-Type', 'text/plain');
});
