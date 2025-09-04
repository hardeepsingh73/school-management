<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>
    <x-slot name="breadcrumbs">
        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
    </x-slot>

    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white border-0 py-3">
            <h3 class="h6 mb-0 fw-medium">Welcome</h3>
        </div>
        <div class="card-body p-4">
            <div class="alert alert-success mb-0">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ __("You're successfully logged in!") }}
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('activity-logs.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-3 card-hover-effect">
                    <div class="card-body p-4 text-center">
                        <i class="bi bi-bar-chart fs-1 mb-2 text-primary"></i>
                        <h5>Total Activity</h5>
                        <p class="fs-3 fw-bold">{{ $totalActivity }}</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-lg-2">
            <a href="{{ route('email-logs.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-3 card-hover-effect">
                    <div class="card-body p-4 text-center">
                        <i class="bi bi-envelope fs-1 mb-2 text-success"></i>
                        <h5>Emails</h5>
                        <p class="fs-3 fw-bold">{{ $emails }}</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-lg-2">
            <a href="{{ route('error-logs.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-3 card-hover-effect">
                    <div class="card-body p-4 text-center">
                        <i class="bi bi-exclamation-triangle fs-1 mb-2 text-danger"></i>
                        <h5>Errors</h5>
                        <p class="fs-3 fw-bold text-danger">{{ $errors }}</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-lg-2">
            <a href="{{ route('api-logs.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-3 card-hover-effect">
                    <div class="card-body p-4 text-center">
                        <i class="bi bi-code-slash fs-1 mb-2 text-info"></i>
                        <h5>API</h5>
                        <p class="fs-3 fw-bold">{{ $apiCalls }}</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('login-history.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-3 card-hover-effect">
                    <div class="card-body p-4 text-center">
                        <i class="bi bi-clock-history fs-1 mb-2 text-warning"></i>
                        <h5>Login History Logs</h5>
                        <p class="fs-3 fw-bold">{{ $loginHistory }}</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div id="calendar"></div>
    <x-slot name="script">
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                $(function() {
                    var $calendarEl = $('#calendar');
                    if ($calendarEl.length === 0) return;
                    var calendar = new Calendar($calendarEl[0], {
                        plugins: [dayGridPlugin],
                        initialView: 'dayGridMonth',

                        events: function(fetchInfo, successCallback, failureCallback) {
                            $.ajax({
                                url: '{{ route('timetables.timetables') }}',
                                type: 'POST',
                                data: JSON.stringify({
                                    start: fetchInfo.startStr,
                                    end: fetchInfo.endStr
                                }),
                                contentType: 'application/json',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                success: function(data) {
                                    successCallback(data);
                                },
                                error: function(xhr) {
                                    failureCallback(xhr);
                                }
                            });
                        },

                        eventDidMount: function(info) {
                            // Set tooltip attributes for Bootstrap
                            $(info.el)
                                .attr('title', info.event.extendedProps.description ||
                                    info.event
                                    .title)
                                .attr('data-bs-toggle', 'tooltip')
                                .attr('data-bs-placement', 'top');

                            // Initialize Bootstrap 5 tooltip
                            new bootstrap.Tooltip(info.el);
                        },

                        eventTimeFormat: {
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: false
                        }
                    });

                    calendar.render();
                });
            });
        </script>
    </x-slot>
</x-app-layout>
