<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmployerController extends Controller
{
    /**
     * Show employer landing page
     */
    public function show($slug)
    {
        // Find employer by slug
        $employers = JobPost::getEmployersWithJobCounts();
        $employer = $employers->firstWhere('slug', $slug);
        
        if (!$employer) {
            abort(404, 'Arbeitgeber nicht gefunden');
        }

        // Get jobs for this employer
        $jobs = JobPost::getJobsByEmployer($employer->arbeitsgeber_name);
        
        // Get additional data
        $cities = JobPost::getCitiesForEmployer($employer->arbeitsgeber_name);
        $categories = JobPost::getCategoriesForEmployer($employer->arbeitsgeber_name);
        
        // Get employer logo
        $logoUrl = null;
        if ($employer->info_fuer_uns && str_contains($employer->info_fuer_uns, 'job-logos/')) {
            $logoUrl = asset('storage/' . $employer->info_fuer_uns);
        } elseif ($employer->job_logo && is_array($employer->job_logo) && !empty($employer->job_logo)) {
            $logoUrl = $employer->job_logo[0]['url'] ?? null;
        }

        // Get website URL
        $websiteUrl = null;
        if ($employer->arbeitsgeber_website && is_array($employer->arbeitsgeber_website) && !empty($employer->arbeitsgeber_website)) {
            $websiteUrl = $employer->arbeitsgeber_website[0];
            if (!str_starts_with($websiteUrl, 'http')) {
                $websiteUrl = 'https://' . $websiteUrl;
            }
        }

        // Get phone number
        $phoneNumber = null;
        if ($employer->arbeitsgeber_tel && is_array($employer->arbeitsgeber_tel) && !empty($employer->arbeitsgeber_tel)) {
            $phoneNumber = $employer->arbeitsgeber_tel[0];
        }

        // SEO Meta data
        $metaTitle = "Jobs bei {$employer->arbeitsgeber_name} - {$employer->job_count} offene Stellen | Ihre-Stelle.de";
        $metaDescription = "Entdecken Sie {$employer->job_count} aktuelle Stellenangebote bei {$employer->arbeitsgeber_name}. Bewerben Sie sich jetzt auf Jobs in " . $cities->pluck('city')->take(3)->implode(', ') . " und weiteren Standorten.";
        
        return view('employers.show', compact(
            'employer', 
            'jobs', 
            'cities', 
            'categories', 
            'logoUrl', 
            'websiteUrl', 
            'phoneNumber',
            'metaTitle',
            'metaDescription'
        ));
    }

    /**
     * List all employers
     */
    public function index()
    {
        $employers = JobPost::getEmployersWithJobCounts();
        
        $metaTitle = "Alle Arbeitgeber - Stellenangebote nach Unternehmen | Ihre-Stelle.de";
        $metaDescription = "Finden Sie Jobs bei Top-Arbeitgebern in Deutschland. Durchsuchen Sie Stellenangebote von über " . $employers->count() . " Unternehmen und bewerben Sie sich direkt.";
        
        return view('employers.index', compact('employers', 'metaTitle', 'metaDescription'));
    }
} 