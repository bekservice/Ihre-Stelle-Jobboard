<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\CityController;

Route::get('/', [JobController::class, 'index'])->name('home');
Route::get('/jobs/{job:slug}', [JobController::class, 'show'])->name('jobs.show');
Route::get('/jobs', [JobController::class, 'search'])->name('jobs.search');

// Saved jobs API
Route::post('/api/saved-jobs', [JobController::class, 'getSavedJobs'])->name('api.saved-jobs');

// Job Alert routes
Route::get('/job-alerts', [App\Http\Controllers\JobAlertController::class, 'create'])->name('job-alerts.create');
Route::post('/job-alerts', [App\Http\Controllers\JobAlertController::class, 'store'])->name('job-alerts.store');
Route::get('/job-alerts/success', [App\Http\Controllers\JobAlertController::class, 'success'])->name('job-alerts.success');
Route::get('/job-alerts/verify/{token}', [App\Http\Controllers\JobAlertController::class, 'verify'])->name('job-alerts.verify');
Route::get('/job-alerts/unsubscribe/{token}', [App\Http\Controllers\JobAlertController::class, 'unsubscribe'])->name('job-alerts.unsubscribe');
Route::get('/job-alerts/manage/{token}', [App\Http\Controllers\JobAlertController::class, 'manage'])->name('job-alerts.manage');
Route::put('/job-alerts/manage/{token}', [App\Http\Controllers\JobAlertController::class, 'update'])->name('job-alerts.update');

// Legal pages
Route::view('/impressum', 'legal.impressum')->name('impressum');
Route::view('/datenschutz', 'legal.datenschutz')->name('datenschutz');

// Application routes
Route::get('/jobs/{job:slug}/bewerben', [App\Http\Controllers\ApplicationController::class, 'show'])->name('application.show');
Route::post('/jobs/{job:slug}/bewerben', [App\Http\Controllers\ApplicationController::class, 'store'])->name('application.store');
Route::get('/jobs/{job:slug}/bewerbung-erfolgreich', [App\Http\Controllers\ApplicationController::class, 'success'])->name('application.success');

// Webhook routes
Route::post('/webhook/sync-job', [WebhookController::class, 'syncSingleJob'])->name('webhook.sync-job');

// Employer landing pages
Route::get('/arbeitgeber', [EmployerController::class, 'index'])->name('employers.index');
Route::get('/arbeitgeber/{slug}', [EmployerController::class, 'show'])->name('employers.show');

// City landing pages  
Route::get('/staedte', [CityController::class, 'index'])->name('cities.index');
Route::get('/jobs/{slug}', [CityController::class, 'show'])->name('cities.show');

// Sitemap routes
Route::get('/sitemap.xml', [SitemapController::class, 'index']);
Route::get('/sitemap-jobs.xml', [SitemapController::class, 'jobs']);
Route::get('/sitemap-employers.xml', [SitemapController::class, 'employers']);
Route::get('/sitemap-cities.xml', [SitemapController::class, 'cities']);

// Robots.txt
Route::get('/robots.txt', function () {
    $content = "User-agent: *\nAllow: /\n\n# Sitemaps\nSitemap: " . url('/sitemap.xml') . "\n\n# Disallow admin areas\nDisallow: /admin/\nDisallow: /api/\n\n# Allow job pages for better indexing\nAllow: /jobs/\nAllow: /\n\n# Crawl-delay for respectful crawling\nCrawl-delay: 1";
    
    return response($content, 200)
        ->header('Content-Type', 'text/plain');
});
