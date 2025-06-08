{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Static Pages -->
    <url>
        <loc>{{ route('home') }}</loc>
        <lastmod>{{ now()->toISOString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ route('jobs.search') }}</loc>
        <lastmod>{{ now()->toISOString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>

    <!-- Job Pages -->
    @foreach($jobs as $job)
    <url>
        <loc>{{ route('jobs.show', $job) }}</loc>
        <lastmod>{{ $job->updated_at->toISOString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach
</urlset> 