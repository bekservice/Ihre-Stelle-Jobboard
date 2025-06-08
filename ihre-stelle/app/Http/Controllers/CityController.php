<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CityController extends Controller
{
    /**
     * Show city landing page
     */
    public function show($slug)
    {
        // Find city by slug
        $cities = JobPost::getCitiesWithJobCounts();
        $city = $cities->firstWhere('slug', $slug);
        
        if (!$city) {
            abort(404, 'Stadt nicht gefunden');
        }

        // Get jobs for this city
        $jobs = JobPost::getJobsByCity($city->city);
        
        // Get additional data
        $topEmployers = JobPost::getTopEmployersForCity($city->city, 10);
        $topCategories = JobPost::getTopCategoriesForCity($city->city, 8);
        
        // Get nearby cities (same postal code prefix)
        $nearbyCities = collect();
        if ($city->postal_code) {
            $postalPrefix = substr($city->postal_code, 0, 2);
            $nearbyCities = JobPost::getCitiesWithJobCounts()
                ->filter(function ($nearbyCity) use ($postalPrefix, $city) {
                    return $nearbyCity->postal_code && 
                           str_starts_with($nearbyCity->postal_code, $postalPrefix) &&
                           $nearbyCity->city !== $city->city;
                })
                ->take(5);
        }

        // Calculate statistics
        $totalJobs = $city->job_count;
        $avgJobsPerEmployer = $topEmployers->count() > 0 ? round($totalJobs / $topEmployers->count(), 1) : 0;
        
        // Get employment types distribution
        $employmentTypes = JobPost::where('is_active', true)
            ->where('city', $city->city)
            ->whereNotNull('job_type')
            ->get()
            ->flatMap(function ($job) {
                return is_array($job->job_type) ? $job->job_type : [$job->job_type];
            })
            ->countBy()
            ->sortDesc()
            ->take(5);

        // SEO Meta data
        $metaTitle = "Jobs in {$city->city} - {$city->job_count} aktuelle Stellenangebote | Ihre-Stelle.de";
        $metaDescription = "Finden Sie {$city->job_count} aktuelle Jobs in {$city->city}. Top-Arbeitgeber: " . $topEmployers->take(3)->pluck('arbeitsgeber_name')->implode(', ') . ". Jetzt bewerben!";
        
        return view('cities.show', compact(
            'city', 
            'jobs', 
            'topEmployers', 
            'topCategories', 
            'nearbyCities',
            'totalJobs',
            'avgJobsPerEmployer',
            'employmentTypes',
            'metaTitle',
            'metaDescription'
        ));
    }

    /**
     * List all cities
     */
    public function index()
    {
        $cities = JobPost::getCitiesWithJobCounts();
        
        // Group cities by first letter for better navigation
        $citiesByLetter = $cities->groupBy(function ($city) {
            return strtoupper(substr($city->city, 0, 1));
        })->sortKeys();
        
        $metaTitle = "Jobs nach Städten - Stellenangebote in ganz Deutschland | Ihre-Stelle.de";
        $metaDescription = "Finden Sie Jobs in über " . $cities->count() . " Städten in Deutschland. Von Berlin bis München - entdecken Sie Stellenangebote in Ihrer Nähe.";
        
        return view('cities.index', compact('cities', 'citiesByLetter', 'metaTitle', 'metaDescription'));
    }
} 