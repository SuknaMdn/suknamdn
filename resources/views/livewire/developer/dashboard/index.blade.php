<div>
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!--begin::Col-->
        <div class="col-xl-4">
            <!--begin::Engage widget 15-->
            <div class="card h-md-100" dir="ltr">
                <!--begin::Body-->
                <div class="card-body d-flex flex-column flex-center">
                    <!--begin::Heading-->
                    <div class="mb-2">

                        <h1 class="fw-semibold text-gray-800 text-center lh-lg">Have you tried
                        <br />new
                        <span class="fw-bolder">Investor Map ?</span></h1>
                        <!--end::Title-->
                        <!--begin::Illustration-->
                        <div class="py-10 text-center">
                            <img src="{{ asset('developer/media/auth/coming-soon.png') }}" class="theme-light-show w-250px" alt="" />
                            <img src="{{ asset('developer/media/auth/coming-soon.png') }}" class="theme-dark-show w-250px" alt="" />
                        </div>
                        <!--end::Illustration-->
                    </div>
                    <!--end::Heading-->
                    <!--begin::Links-->
                    <div class="text-center mb-1">
                        <!--begin::Link-->
                        <a class="btn btn-sm btn-dark me-2 disabled" disabled data-bs-target="#kt_modal_create_app" data-bs-toggle="modal">Coming soon...</a>
                        <!--end::Link-->
                    </div>
                    <!--end::Links-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Engage widget 15-->
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-xl-8">
            <!--begin::Chart Widget 46-->
            <div class="card card-flush h-lg-100">

                <div class="card-header pt-5">
                    <div class="d-flex flex-center">
                        <h3 class="card-title align-items-start">
                            <span class="card-label fw-bold text-gray-800">Units Sales</span>
                        </h3>
                        <div class="d-flex align-items-center px-5">
                            <div class="d-flex align-items-center me-6">
                                <span class="rounded-1 bg-gray-800 me-2 h-10px w-10px"></span>
                                <span class="fw-semibold fs-6 text-gray-600">Sold</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="rounded-1 bg-gray-500 me-2 h-10px w-10px"></span>
                                <span class="fw-semibold fs-6 text-gray-600">Pending</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0 px-0">
                    <div id="kt_charts_widget_46" class="min-h-auto ps-4 pe-6 mb-3" style="height: 350px"></div>
                </div>

            </div>
            <!--end::Chart Widget 46-->
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
    <!--begin::Row-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <div class="col-xl-12">
            @livewire('developer.dashboard.orders-by-month')
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
    <!--begin::Row-->
    {{-- <div class="row g-5 g-xl-10 g-xl-10">
        <div class="col-xl-4">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-7 mb-3">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">Our Fleet Tonnage</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-6">Total 1,247 vehicles</span>
                    </h3>
                    <div class="card-toolbar">
                        <a href="#" class="btn btn-sm btn-light" data-bs-toggle='tooltip' data-bs-dismiss='click' data-bs-custom-class="tooltip-inverse" title="Logistics App is coming soon">Review Fleet</a>
                    </div>
                </div>
                <div class="card-body pt-4">
                    <div class="d-flex flex-stack">
                        <div class="d-flex align-items-center me-5">
                            <div class="symbol symbol-40px me-4">
                                <span class="symbol-label">
                                    <i class="ki-outline ki-ship text-gray-600 fs-1"></i>
                                </span>
                            </div>
                            <div class="me-5">
                                <a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">Ships</a>
                                <span class="text-gray-500 fw-semibold fs-7 d-block text-start ps-0">234 Ships</span>
                            </div>
                        </div>
                        <div class="text-gray-500 fw-bold fs-7 text-end">
                        <span class="text-gray-800 fw-bold fs-6 d-block">2,345,500</span>
                        Tons</div>
                    </div>
                    <div class="separator separator-dashed my-5"></div>
                    <div class="d-flex flex-stack">

                        <div class="d-flex align-items-center me-5">

                            <div class="symbol symbol-40px me-4">
                                <span class="symbol-label">
                                    <i class="ki-outline ki-truck text-gray-600 fs-1"></i>
                                </span>
                            </div>

                            <div class="me-5">
                                <a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">Trucks</a>
                                <span class="text-gray-500 fw-semibold fs-7 d-block text-start ps-0">1,460 Trucks</span>
                            </div>
                        </div>
                        <div class="text-gray-500 fw-bold fs-7 text-end">
                        <span class="text-gray-800 fw-bold fs-6 d-block">457,200</span>
                        Tons</div>
                    </div>
                    <div class="separator separator-dashed my-5"></div>
                    <div class="d-flex flex-stack">

                        <div class="d-flex align-items-center me-5">

                            <div class="symbol symbol-40px me-4">
                                <span class="symbol-label">
                                    <i class="ki-outline ki-airplane-square text-gray-600 fs-1"></i>
                                </span>
                            </div>

                            <div class="me-5">

                                <a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">Planes</a>
                                <span class="text-gray-500 fw-semibold fs-7 d-block text-start ps-0">8 Aircrafts</span>
                            </div>

                        </div>

                        <div class="text-gray-500 fw-bold fs-7 text-end">
                        <span class="text-gray-800 fw-bold fs-6 d-block">1,240</span>
                        Tons</div>
                    </div>

                    <div class="separator separator-dashed my-5"></div>

                    <div class="d-flex flex-stack">

                        <div class="d-flex align-items-center me-5">

                            <div class="symbol symbol-40px me-4">
                                <span class="symbol-label">
                                    <i class="ki-outline ki-bus text-gray-600 fs-1"></i>
                                </span>
                            </div>

                            <div class="me-5">

                                <a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">Trains</a>
                                <span class="text-gray-500 fw-semibold fs-7 d-block text-start ps-0">36 Trains</span>
                            </div>
                        </div>

                        <div class="text-gray-500 fw-bold fs-7 text-end">
                        <span class="text-gray-800 fw-bold fs-6 d-block">804,300</span>
                        Tons</div>
                    </div>
                    <div class="text-center pt-9">
                        <a href="apps/ecommerce/catalog/add-product.html" class="btn btn-primary">Add Vehicle</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-8 mb-5 mb-xl-10">
            <div class="card card-flush h-md-100">
                <!--begin::Header-->
                <div class="card-header pt-7">

                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-900">World Sales</span>
                        <span class="text-gray-500 pt-2 fw-semibold fs-6">Top Selling Countries</span>
                    </h3>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body d-flex flex-center">
                    <!--begin::Map container-->
                    <div id="kt_maps_widget_1_map" class="w-100 h-350px"></div>
                    <!--end::Map container-->
                </div>
                <!--end::Body-->
            </div>
        </div>
    </div> --}}
</div>

@push('scripts')
<script>

    document.addEventListener('DOMContentLoaded', function() {
        var KTChartsWidget46 = (function () {
            var e = { self: null, rendered: !1 },
                t = function () {
                    var t = document.getElementById("kt_charts_widget_46");
                    if (t) {
                        var a = t.hasAttribute("data-kt-negative-color") ? t.getAttribute("data-kt-negative-color") : KTUtil.getCssVariableValue("--bs-gray-500"),
                            l = parseInt(KTUtil.css(t, "height")),
                            r = KTUtil.getCssVariableValue("--bs-gray-900"),
                            o = KTUtil.getCssVariableValue("--bs-border-dashed-color");

                        // Dynamically fetch unit order data
                        var unitsSoldData = @json($unitsSoldByMonth);
                        var unitsNotSoldData = @json($unitsNotSoldByMonth);

                        var i = {
                            series: [
                                { name: "Units Sold", data: unitsSoldData },
                                { name: "Units Not Sold", data: unitsNotSoldData },
                            ],
                            chart: { fontFamily: "inherit", type: "bar", stacked: !0, height: l, toolbar: { show: !1 } },

                            plotOptions: { bar: { columnWidth: "35%", barHeight: "70%", borderRadius: [4, 4] } },
                            legend: { show: !1 },
                            dataLabels: { enabled: !1 },
                            xaxis: {
                                categories: @json($monthLabels),
                                axisBorder: { show: !1 },
                                axisTicks: { show: !1 },
                                tickAmount: 10,
                                labels: {
                                    style: {
                                        colors: [r],
                                        fontSize: "12px"
                                    }
                                },
                                crosshairs: { show: !1 },
                            },
                            yaxis: {
                                min: 0,
                                max: 150,
                                tickAmount: 5,
                                labels: {
                                    style: { colors: [r], fontSize: "12px" },
                                    formatter: function (e) {
                                        return parseInt(e);
                                    },
                                },
                            },
                            fill: { opacity: 1 },
                            states: { normal: { filter: { type: "none", value: 0 } }, hover: { filter: { type: "none", value: 0 } }, active: { allowMultipleDataPointsSelection: !1, filter: { type: "none", value: 0 } } },
                            tooltip: {
                                style: { fontSize: "12px", borderRadius: 4 },
                                    y: {
                                        formatter: function (e) {
                                        return e > 0 ? e + " units" : Math.abs(e) + " units";
                                    },
                                },
                            },
                            colors: [KTUtil.getCssVariableValue("--bs-gray-800"), a],
                            grid: { borderColor: o, strokeDashArray: 4, yaxis: { lines: { show: !0 } } },
                        };

                        (e.self = new ApexCharts(t, i)),
                            setTimeout(function () {
                                e.self.render(), (e.rendered = !0);
                            }, 200);
                    }
                };

            return {
                init: function () {
                    t(),
                        KTThemeMode.on("kt.thememode.change", function () {
                            e.rendered && e.self.destroy(), t();
                        });
                },
            };
        })();

        KTUtil.onDOMContentLoaded(function () {
            KTChartsWidget46.init();
        });
    });
</script>
@endpush
