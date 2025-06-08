<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;

// Webhook routes (CSRF-exempt)
Route::post('/webhook/sync-job', [WebhookController::class, 'syncSingleJob'])->name('api.webhook.sync-job'); 