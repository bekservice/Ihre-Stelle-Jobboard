@php echo '<?xml version="1.0" encoding="UTF-8"?>'; @endphp
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>{{ url('/sitemap-jobs.xml') }}</loc>
        <lastmod>{{ now()->toISOString() }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ url('/sitemap-employers.xml') }}</loc>
        <lastmod>{{ now()->toISOString() }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ url('/sitemap-cities.xml') }}</loc>
        <lastmod>{{ now()->toISOString() }}</lastmod>
    </sitemap>
</sitemapindex> 