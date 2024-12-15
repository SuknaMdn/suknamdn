<div>
    <div style="position: absolute;top: 79px;left: 0;width: 100%;height: calc(100% - 79px);">
        <div id="projectMap" style="width: 100%; height:  calc(100%);"></div>
    </div>
</div>

@push('scripts')
<script src='https://api.mapbox.com/mapbox-gl-js/v3.0.1/mapbox-gl.js'></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ensure projects is an array

        const projectsResponse = @json($projects);
        const mapProjects = projectsResponse.data || [];
        console.log(mapProjects)

        // Rest of your existing Mapbox initialization code...
        mapboxgl.accessToken = '{{ env("MAPBOX_ACCESS_TOKEN") }}';

        const map = new mapboxgl.Map({
            container: 'projectMap',
            style: 'mapbox://styles/mapbox/navigation-day-v1',
            center: [
                    {{ $projects->avg('longitude') }},
                    {{ $projects->avg('latitude') }}
                ],
            zoom: 10
        });

        // Add navigation controls
        map.addControl(new mapboxgl.NavigationControl());

        // Marker creation
        mapProjects.forEach(function(project) {
            // Validate coordinates
            if (!project.longitude || !project.latitude) {
                console.warn('Invalid coordinates for project:', project);
                return;
            }

            new mapboxgl.Marker({
                color: '#333333' // Set marker color to black
            })
                .setLngLat([
                    parseFloat(project.longitude),
                    parseFloat(project.latitude)
                ])
                .setPopup(new mapboxgl.Popup().setHTML(`
                    <div style="width: 240px;">
                        <div style="position: relative;">
                            <img src="${project.images && project.images.length > 0 ? '/storage/' + project.images[0] : ''}"
                                 style="width: 100%; height: 120px; object-fit: cover; border-radius: 8px 8px 0 0;"
                                 onerror="this.src='{{ asset('assets/media/placeholder-image.jpg') }}'">
                            <div style="position: absolute; top: 10px; right: 10px;">
                                <span style="background: ${project.is_active ? '#50CD89' : '#F1416C'};
                                           color: white;
                                           padding: 4px 8px;
                                           border-radius: 6px;
                                           font-size: 12px;">
                                    ${project.is_active ? 'Active' : 'Not Active'}
                                </span>
                            </div>
                        </div>
                        <div style="padding: 12px;">
                            <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: bold;">${project.title}
                                <span style="background: #F1F1F4;color: #7E8299;padding: 4px 8px;border-radius: 6px;font-size: 12px;">
                                    شقة
                                </span>
                            </h3>
                            <div style="margin-bottom: 8px;">
                                <p class="text-muted">${project.description}</p>
                            </div>

                            <a href="/developer/project/${project.slug}"
                               style="display: block;
                                      text-align: center;
                                      background: #000000;
                                      color: white;
                                      padding: 8px;
                                      border-radius: 6px;
                                      margin-top: 12px;
                                      text-decoration: none;">
                                View Details
                            </a>
                        </div>
                    </div>
                `))
                .addTo(map);
        });
    });
</script>
@endpush
