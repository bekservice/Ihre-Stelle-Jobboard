<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use Illuminate\View\View;

class JobController extends Controller
{
    public function index(): View
    {
        $jobs = JobPost::query()->where('is_active', true)->latest()->paginate(10);
        return view('jobs.index', compact('jobs'));
    }

    public function show(string $slug): View
    {
        $job = JobPost::where('slug', $slug)->firstOrFail();
        return view('jobs.show', compact('job'));
    }
}
