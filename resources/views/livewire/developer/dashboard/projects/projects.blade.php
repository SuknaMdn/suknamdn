<div>

    <div>
        <div id="kt_app_toolbar" class="app-toolbar pt-7 pt-lg-10">
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7">
                        <li class="breadcrumb-item text-gray-700 fw-bold lh-1 mx-n1">
                            <a href="index.html" class="text-hover-primary">
                                <i class="ki-outline ki-home text-gray-700 fs-6"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <i class="ki-outline ki-right fs-7 text-gray-700"></i>
                        </li>
                        <li class="breadcrumb-item text-gray-700 fw-bold lh-1 mx-n1">Dashboard</li>
                        <li class="breadcrumb-item">
                            <i class="ki-outline ki-right fs-7 text-gray-700"> </i>
                        </li>
                        <li class="breadcrumb-item text-gray-500 mx-n1">My Projects</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!--begin::Toolbar-->
    <div class="d-flex flex-wrap flex-stack mb-6">
        <!--begin::Heading-->
        <h3 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bolder fs-3 my-5">My Projects</h3>
        <!--end::Heading-->
        <!--begin::Actions-->
        <div class="d-flex flex-wrap align-items-center my-2">

            <div wire:loading wire:target="selected_is_active, selected_project_type" class="spinner-border spinner-border-sm mx-5" role="status" aria-hidden="true"></div>
            <div class="me-4">
                <!--begin::Select-->
                <select wire:model.live="selected_is_active" class="form-select form-select-sm form-select-solid w-125px">
                    <option value="">All Status</option>
                    <option value="1">Active</option>
                    <option value="0">Not Active</option>
                </select>
                <!--end::Select-->
            </div>

            <div class="me-4">
                <!--begin::Select-->
                <select wire:model.live="selected_project_type" class="form-select form-select-sm form-select-solid w-125px">
                    <option value="">All Types</option>
                    @foreach ($projectTypes as $projectType)
                        <option value="{{ $projectType->id }}">{{ $projectType->name }}</option>
                    @endforeach
                </select>
                <!--end::Select-->
            </div>

            <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">New Project</a>
        </div>
        <!--end::Actions-->
    </div>
    <!--end::Toolbar-->


    <!--begin::Stats-->
    <div class="row gx-6 gx-xl-9 mb-5">
        <div class="col-lg-6 col-xxl-4">
            <!--begin::Card-->
            <div class="card h-100">
                <!--begin::Card body-->
                <div class="card-body p-9">
                    <!--begin::Heading-->
                    <div class="fs-2hx fw-bold">{{ $projects->count() }}</div>
                    <div class="fs-4 fw-semibold text-gray-500 mb-7">Current Projects</div>
                    <!--end::Heading-->
                    <!--begin::Wrapper-->
                    <div class="d-flex flex-wrap">
                        <!--begin::Chart-->
                        <div class="d-flex flex-center h-100px w-100px me-9 mb-5" wire:ignore>
                            <canvas id="kt_project_list_chart"></canvas>
                        </div>
                        <!--end::Chart-->
                        <!--begin::Labels-->
                        <div class="d-flex flex-column justify-content-center flex-row-fluid pe-11 mb-5">
                            <!--begin::Label-->
                            <div class="d-flex fs-6 fw-semibold align-items-center mb-3">
                                <div class="bullet bg-primary me-3"></div>
                                <div class="text-gray-500">Active</div>
                                <div class="ms-auto fw-bold text-gray-700">{{ $projects->where('is_active', 1)->count() }}</div>
                            </div>
                            <!--end::Label-->
                            <!--begin::Label-->
                            <div class="d-flex fs-6 fw-semibold align-items-center mb-3">
                                <div class="bullet bg-success me-3"></div>
                                <div class="text-gray-500">Completed <span class="text-muted small">(no units to sell)</span></div>
                                <div class="ms-auto fw-bold text-gray-700">{{ $projectsWithoutCaseZero }}</div>
                            </div>
                            <!--end::Label-->
                            <!--begin::Label-->
                            <div class="d-flex fs-6 fw-semibold align-items-center">
                                <div class="bullet bg-danger me-3"></div>
                                <div class="text-gray-500">Not Active</div>
                                <div class="ms-auto fw-bold text-gray-700">{{ $projects->where('is_active', 0)->count() }}</div>
                            </div>
                            <!--end::Label-->
                        </div>
                        <!--end::Labels-->
                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <div class="col-lg-6 col-xxl-4">
            <!--begin::Budget-->
            <div class="card h-100">
                <div class="card-body p-9">
                    <div class="fs-4 fw-semibold text-gray-900 mb-7">Projects Units</div>
                    <div class="fs-6 d-flex justify-content-between mb-4">
                        <div class="fw-semibold text-gray-500">All Units</div>
                        <div class="d-flex fw-bold">{{ $allUnits }} <span class="text-gray-500 ms-1"> units</span></div>
                    </div>
                    <div class="separator separator-dashed"></div>
                    <div class="fs-6 d-flex justify-content-between my-4">
                        <div class="fw-semibold text-gray-500">Sold Units</div>
                        <div class="d-flex fw-bold">{{ $unitsSold }} <span class="text-gray-500 ms-1"> units</span></div>
                    </div>
                    <div class="separator separator-dashed"></div>
                    <div class="fs-6 d-flex justify-content-between mt-4">
                        <div class="fw-semibold text-gray-500">Not Sold Units</div>
                        <div class="d-flex fw-bold">{{ $unitsNotSold }} <span class="text-gray-500 ms-1"> units</span></div>
                    </div>
                </div>
            </div>
            <!--end::Budget-->
        </div>

        <div class="col-lg-6 col-xxl-4">
            <!--begin::Budget-->
            <div class="card h-100">
                <div class="card-body p-0">
                    <a href="{{ route('developer.projects.fullmap') }}" class="btn btn-light border btn-sm position-absolute z-10 mt-2 ml-2">Full map</a>
                    <div wire:ignore>
                        <div
                            id="projectMap"
                            class="rounded"
                            style="width: 100%; height: 270px"
                        ></div>
                    </div>
                </div>
            </div>
            <!--end::Budget-->
        </div>
    </div>
    <!--end::Stats-->
    <!--begin::Row-->
    <div class="row g-6 g-xl-9">
        <!--begin::Col-->
        @foreach ($projects as $project)
        <div class="col-md-6 col-xl-4" wire:key="{{ $project->id }}">
            <!--begin::Card-->
            <a href="{{ route('developer.projects.show', $project->slug) }}" class="card border-hover-primary">
                <!--begin::Card header-->
                <div class="card-header border-0">
                    <!--begin::Card Title-->
                    <div class="card-title m-0">
                        <!--begin::Avatar-->
                        <div class="symbol bg-light">
                            <img src="{{ asset($developer->logo) }}" alt="image" />
                        </div>
                        <!--end::Avatar-->
                    </div>
                    <!--end::Car Title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <span class="badge badge-light-dark fw-bold me-auto px-4 py-3">{{ $project->propertyType->name }}</span>
                        <span class="badge {{ $project->is_active ? 'badge-light-success' : 'badge-light-danger' }} fw-bold px-4 py-3 ms-2">{{ $project->is_active ? 'active' : 'not Action' }}</span>
                    </div>

                    <!--end::Card toolbar-->
                </div>
                <!--end:: Card header-->
                <!--begin:: Card body-->
                <div class="card-body p-9 pt-1">
                    <!--begin::Name-->
                    <div class="fs-3 fw-bold text-gray-900">{{ $project->title }}</div>
                    <!--end::Name-->
                    <!--begin::Description-->
                    <p class="text-gray-500 fw-semibold fs-5 mt-1 mb-7">
                        {{ \Illuminate\Support\Str::words(strip_tags($project->description), 50, '...') }}
                        <br>
                        <span class="fs-6 text-gray-500">{{ $project->created_at->format('M d, Y') }}</span>
                    </p>
                    <!--end::Description-->
                    <!--begin::Info-->
                    <div class="d-flex flex-wrap mb-5">
                        <!--begin::Due-->
                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-7 mb-3">
                            <div class="fs-6 text-gray-800 fw-bold">{{ $project->units->count() }}</div>
                            <div class="fw-semibold text-gray-500">units</div>
                        </div>
                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 mb-3">
                            <div class="fs-6 text-gray-800 fw-bold">SAR {{ number_format($project->units->where('case', 1)->sum('total_amount'), 2) }}</div>
                            <div class="fw-semibold text-gray-500">Sold</div>
                        </div>
                    </div>
                    <!--end::Info-->
                    <!--begin::Progress-->
                    <div class="h-4px w-100 bg-light" data-bs-toggle="tooltip" title="This project {{ number_format($this->calculateProgressPercentage($project), 2) }}% sold">
                        <div class="{{ $this->calculateProgressPercentage($project) >= 50 ? 'bg-success' : 'bg-primary' }} rounded h-4px" role="progressbar" style="width: {{ $this->calculateProgressPercentage($project) }}%" aria-valuenow="{{ $this->calculateProgressPercentage($project) }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <!--end:: Card body-->
            </a>
            <!--end::Card-->
        </div>
        @endforeach

    </div>
    <!--end::Row-->
    <!--begin::Pagination-->
    <div class="d-flex flex-stack flex-wrap pt-10 justify-content-center mb-7">
        {{ $projects->links('pagination::bootstrap-5') }}
    </div>
    <!--end::Pagination-->
</div>

@push('scripts')
<script src='https://api.mapbox.com/mapbox-gl-js/v3.0.1/mapbox-gl.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    var chartElement = document.getElementById("kt_project_list_chart");

    if (chartElement) {
        var ctx = chartElement.getContext("2d");

        new Chart(ctx, {
            type: "doughnut",
            data: {
                datasets: [{
                    data: [{{ $activeProjects }}, {{ $projectsWithoutCaseZero }}, {{ $inactiveProjects }}],
                    backgroundColor: ["#000000", "#50CD89", "#FF5757"]
                }],
                labels: ["Active", "Completed", "Not Active"]
            },
            options: {
                chart: {
                    fontFamily: "inherit"
                },
                borderWidth: 0,
                cutout: "75%",
                cutoutPercentage: 65,
                responsive: true,
                maintainAspectRatio: false,
                title: {
                    display: false
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                },
                stroke: {
                    width: 0
                },
                tooltips: {
                    enabled: true,
                    intersect: false,
                    mode: "nearest",
                    bodySpacing: 5,
                    yPadding: 10,
                    xPadding: 10,
                    caretPadding: 0,
                    displayColors: false,
                    backgroundColor: "#20D489",
                    titleFontColor: "#ffffff",
                    cornerRadius: 4,
                    footerSpacing: 0,
                    titleSpacing: 0
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    } else {
        console.error("Could not find element with id 'kt_project_list_chart'");
    }
});

</script>
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
            style: 'mapbox://styles/mapbox/light-v11',
            center: [
                    // Set to center of your primary development area
                    {{ $projects->avg('longitude') }},
                    {{ $projects->avg('latitude') }}
                ],
            zoom: 6
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
                color: '#F0C648' // Set marker color to black
            })
                .setLngLat([
                    parseFloat(project.longitude),
                    parseFloat(project.latitude)
                ])
                .setPopup(new mapboxgl.Popup().setHTML(`
                    <div>
                        <h3>${project.title}</h3>
                        <p>Developer: ${project.slug}</p>
                        <p>Status: ${project.status}</p>
                    </div>
                `))
                .addTo(map);
        });
    });
</script>
@endpush
