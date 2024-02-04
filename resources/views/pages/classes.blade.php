@extends('layouts.page')
@section('content')
    <section class="ttm-row clearfix">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title">
                        <div class="title-header text-center">
                            <h4>TIMETABLE</h4>
                            <h2 class="title">Weekly Class Schedule</h2>
                        </div>
                    </div>
                    <div class="section-content">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                height: 'auto',
                themeSystem: 'bootstrap',
                firstDay: '1',
                timeZone: 'Europe/London',
                initialView: 'listWeek',
                headerToolbar: {
                    start: 'title',
                    center: '',
                    end: 'today prev next'
                },
                buttonIcons: {
                    close: 'fa-times',
                    prev: 'fa-angle-left',
                    next: 'fa-angle-right'
                },
                titleFormat: {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                },
                dayMaxEvents: true,
                events: {
                    url: "{{ route('class-schedules.list') }}",
                    extraParams: {
                        hasClass: true
                    },
                    startParam: 'startOfWeek',
                    endParam: 'endOfWeek',
                },
                eventTimeFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                },
            });

            calendar.render();
        });
    </script>
@endsection
