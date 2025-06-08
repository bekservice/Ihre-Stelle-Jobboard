@props(['jobs' => collect(), 'height' => '400px'])

<div class="mapbox-container" style="height: {{ $height }};">
    <div id="job-map" style="height: 100%; width: 100%;"></div>
</div>

<!-- Mapbox JavaScript -->
<script src='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Mapbox
    mapboxgl.accessToken = 'pk.eyJ1IjoiYmVrc2VydmljZSIsImEiOiJjazl2NnB3bjAwOG82M3BydWxtazQyczdkIn0.w_HtU8Vi9uRDtZa0Qy3FqA';
    
    const map = new mapboxgl.Map({
        container: 'job-map',
        style: 'mapbox://styles/mapbox/light-v11',
        center: [10.4515, 51.1657], // Deutschland Zentrum
        zoom: 6
    });
    
    // Make map available globally for resize events
    window.map = map;
    
    // Add navigation controls
    map.addControl(new mapboxgl.NavigationControl());
    
    // Jobs data from Laravel
    const jobs = @json($jobs);
    console.log('Jobs data for map:', jobs);
    
    map.on('load', function() {
        // Add jobs as markers
        jobs.forEach(function(job) {
            if (job.longitude && job.latitude && job.longitude !== '' && job.latitude !== '') {
                // Create custom marker
                const markerElement = document.createElement('div');
                markerElement.className = 'map-marker';
                markerElement.style.cssText = `
                    width: 24px;
                    height: 24px;
                    background: linear-gradient(135deg, #C4704A 0%, #ED8936 100%);
                    border: 2px solid white;
                    border-radius: 50%;
                    cursor: pointer;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                    transition: transform 0.2s ease;
                `;
                
                // Add hover effect
                markerElement.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.2)';
                });
                markerElement.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
                
                // Create popup content
                const popupContent = `
                    <div class="p-3 max-w-xs">
                        <h3 class="font-semibold text-primary-blue mb-2">${job.title}</h3>
                        ${job.arbeitsgeber_name ? `<p class="text-gray-600 text-sm mb-1">${job.arbeitsgeber_name}</p>` : ''}
                        ${job.city ? `<p class="text-gray-500 text-sm mb-2">${job.city}</p>` : ''}
                        <a href="/jobs/${job.slug}" class="btn-primary text-xs px-3 py-1 rounded inline-block">
                            Details ansehen
                        </a>
                    </div>
                `;
                
                const popup = new mapboxgl.Popup({
                    offset: 25,
                    closeButton: true,
                    closeOnClick: false
                }).setHTML(popupContent);
                
                // Add marker to map
                try {
                    const lng = parseFloat(job.longitude);
                    const lat = parseFloat(job.latitude);
                    
                    if (!isNaN(lng) && !isNaN(lat)) {
                        new mapboxgl.Marker(markerElement)
                            .setLngLat([lng, lat])
                            .setPopup(popup)
                            .addTo(map);
                        console.log('Added marker for job:', job.title, 'at', lng, lat);
                    } else {
                        console.error('Invalid coordinates for job:', job.title, job.longitude, job.latitude);
                    }
                } catch (error) {
                    console.error('Error creating marker for job:', job.title, error);
                }
            }
        });
        
        // Fit map to show all markers if jobs exist
        if (jobs.length > 0) {
            const validJobs = jobs.filter(job => job.longitude && job.latitude && job.longitude !== '' && job.latitude !== '');
            console.log('Valid jobs with coordinates:', validJobs.length);
            
            if (validJobs.length > 0) {
                const bounds = new mapboxgl.LngLatBounds();
                validJobs.forEach(job => {
                    try {
                        const lng = parseFloat(job.longitude);
                        const lat = parseFloat(job.latitude);
                        if (!isNaN(lng) && !isNaN(lat)) {
                            bounds.extend([lng, lat]);
                        }
                    } catch (error) {
                        console.error('Error parsing coordinates for job:', job.id, error);
                    }
                });
                
                try {
                    map.fitBounds(bounds, { padding: 50 });
                } catch (error) {
                    console.error('Error fitting bounds:', error);
                    // Fallback to Germany center
                    map.setCenter([10.4515, 51.1657]);
                    map.setZoom(6);
                }
            } else {
                console.log('No valid coordinates found, using default view');
            }
        } else {
            console.log('No jobs data available for map');
        }
    });
    
    // Add click event to map for location search
    map.on('click', function(e) {
        const coordinates = e.lngLat;
        
        // Reverse geocoding to get location name
        fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${coordinates.lng},${coordinates.lat}.json?access_token=${mapboxgl.accessToken}&language=de&country=DE`)
            .then(response => response.json())
            .then(data => {
                if (data.features && data.features.length > 0) {
                    const place = data.features[0];
                    const placeName = place.place_name;
                    
                    // Update search form if it exists
                    const locationInput = document.querySelector('input[name="location"]');
                    if (locationInput) {
                        locationInput.value = placeName;
                    }
                    
                    // Show popup with location info
                    new mapboxgl.Popup()
                        .setLngLat(coordinates)
                        .setHTML(`
                            <div class="p-2">
                                <p class="text-sm font-medium">${placeName}</p>
                                <button onclick="searchJobsAtLocation('${placeName}')" class="btn-primary text-xs px-2 py-1 rounded mt-1">
                                    Jobs hier suchen
                                </button>
                            </div>
                        `)
                        .addTo(map);
                }
            })
            .catch(error => console.error('Geocoding error:', error));
    });
});

// Function to search jobs at clicked location
function searchJobsAtLocation(location) {
    const searchForm = document.querySelector('#job-search-form');
    if (searchForm) {
        const locationInput = searchForm.querySelector('input[name="location"]');
        if (locationInput) {
            locationInput.value = location;
            searchForm.submit();
        }
    } else {
        // Redirect to search page with location
        window.location.href = `/jobs?location=${encodeURIComponent(location)}`;
    }
}
</script> 