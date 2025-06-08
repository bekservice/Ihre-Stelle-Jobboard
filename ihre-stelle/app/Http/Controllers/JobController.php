<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobController extends Controller
{
    public function index(): View
    {
        $featuredJobs = JobPost::where('is_active', true)
            ->whereNotNull('description')
            ->latest('created_at')
            ->take(6)
            ->get();

        $totalJobs = JobPost::where('is_active', true)->count();
        
        $categories = JobPost::where('is_active', true)
            ->whereNotNull('kategorie')
            ->selectRaw('kategorie, COUNT(*) as count')
            ->groupBy('kategorie')
            ->orderBy('count', 'desc')
            ->take(8)
            ->get();

        $locations = JobPost::where('is_active', true)
            ->whereNotNull('city')
            ->selectRaw('city, COUNT(*) as count')
            ->groupBy('city')
            ->orderBy('count', 'desc')
            ->take(8)
            ->get();

        return view('home', compact('featuredJobs', 'totalJobs', 'categories', 'locations'));
    }

    public function show(JobPost $job): View
    {
        if (!$job->is_active) {
            abort(404);
        }

        $relatedJobs = JobPost::where('is_active', true)
            ->where('id', '!=', $job->id)
            ->where(function($query) use ($job) {
                if ($job->kategorie) {
                    $query->where('kategorie', $job->kategorie);
                }
                if ($job->city) {
                    $query->orWhere('city', $job->city);
                }
            })
            ->take(4)
            ->get();

        return view('jobs.show', compact('job', 'relatedJobs'));
    }

    public function search(Request $request): View
    {
        $query = JobPost::where('is_active', true);

        if ($request->filled('q')) {
            $searchTerm = $request->get('q');
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('city', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('kategorie', $request->get('category'));
        }

        if ($request->filled('location')) {
            $location = $request->get('location');
            $query->where(function($q) use ($location) {
                $q->where('city', 'like', "%{$location}%")
                  ->orWhere('postal_code', 'like', "%{$location}%");
            });
        }

        if ($request->filled('job_type')) {
            $jobType = $request->get('job_type');
            $query->whereJsonContains('job_type', $jobType);
        }

        $jobs = $query->latest('created_at')->paginate(20);
        
        $categories = JobPost::where('is_active', true)
            ->whereNotNull('kategorie')
            ->distinct()
            ->pluck('kategorie')
            ->sort();

        $locations = JobPost::where('is_active', true)
            ->whereNotNull('city')
            ->distinct()
            ->pluck('city')
            ->sort();

        // Get all unique job types from JSON arrays
        $allJobTypes = JobPost::where('is_active', true)
            ->whereNotNull('job_type')
            ->get()
            ->pluck('job_type')
            ->filter()
            ->flatten()
            ->unique()
            ->sort()
            ->values();
        
        $jobTypes = $allJobTypes;

        return view('jobs.search', compact('jobs', 'categories', 'locations', 'jobTypes'));
    }
}
