<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $jobs = JobPost::where('is_active', true)
            ->whereNotNull('slug')
            ->latest('updated_at')
            ->get();

        $employers = JobPost::getEmployersWithJobCounts();
        $cities = JobPost::getCitiesWithJobCounts();

        $content = view('sitemap.index', compact('jobs', 'employers', 'cities'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }

    public function jobs()
    {
        $jobs = JobPost::where('is_active', true)
            ->whereNotNull('slug')
            ->latest('updated_at')
            ->get();

        $content = view('sitemap.jobs', compact('jobs'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }

    public function employers()
    {
        $employers = JobPost::getEmployersWithJobCounts();

        $content = view('sitemap.employers', compact('employers'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }

    public function cities()
    {
        $cities = JobPost::getCitiesWithJobCounts();

        $content = view('sitemap.cities', compact('cities'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }
}
