<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        class-schedule-{{ $classSchedule->label }}
    </title>
    <style>
        body {
            font-size: 12px;
            font-family: Helvetica, sans-serif;
        }

        input[type="text"] {
            height: 15px;
        }

        input[type="text"],
        textarea {
            width: 100%;
            margin: 0px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
        }

        table {
            width: 100%;
        }

        .table td,
        .table th {
            padding: 2px 5px;
        }

        .table th.border,
        .active-table th {
            border-bottom: 1px solid gray;
        }

        .table tbody td.border,
        .active-table tbody td {
            border-bottom: 1px solid gray;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0px;
            right: 0px;
            height: 30px;
        }

        .text-right {
            text-align: right;
        }

        .selected {
            background: #AAFFEB !important;
        }

        .right-heading {
            margin: 0;
            color: #999;
        }

        .no-border {
            border: none !important;
        }

        #signed-off {
            position: absolute;
            top: -5px
        }
    </style>
</head>

<body>
    <main>
        <table cellspacing="0">
            <tr>
                <td colspan="100%">
                    <h2 class="right-heading">{{ config('app.name') }} Class Register</h2>
                    <div>Class Name: @if ($classSchedule->class)
                            {{ $classSchedule->class->name }}
                        @endif
                    </div>
                    <div>Location: @if ($classSchedule->location)
                            {{ $classSchedule->location->label }}
                        @endif
                    </div>
                </td>
            </tr>
        </table>
        <table cellspacing="0">
            <tr>
                <td>Day: {{ $classSchedule->day->value }}</td>
                <td>Date: {{ $classSchedule->date_at_formated }}</td>
                <td>Start Time: {{ $classSchedule->start_at_formated }}</td>
                <td>End Time: {{ $classSchedule->end_at_formated }}</td>
                <td class="text-right">Instructor: @if ($classSchedule->instructor)
                        {{ $classSchedule->instructor->name }}
                    @endif
                </td>
            </tr>
        </table>
        <table style="margin: 15px 0px" cellspacing="0">
            <tbody>
                <tr>
                    <td>
                        <table cellspacing="0" class="active-table table">
                            <thead>
                                <tr>
                                    <th width="10px" align="left">S/N</th>
                                    <th align="left">Name</th>
                                    <th width="50px" align="left">Status</th>
                                    <th class="no-border" width="10px">&nbsp;</th>
                                    <th class="no-border" width="110px" align="center">
                                        Confirm Attendance
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $active_bookings = $classSchedule->active_bookings
                                        ->sortBy(function ($item, $key) {
                                            return $item->user->last_name;
                                        })
                                        ->values()
                                        ->all();
                                @endphp
                                @for ($id = 1; $id <= $classSchedule->capacity; $id++)
                                    @php
                                        $booking = isset($active_bookings[$id - 1]) ? $active_bookings[$id - 1] : false;
                                    @endphp
                                    @if ($booking)
                                        <tr>
                                            <td>{{ $id }}</td>
                                            <td>
                                                <span class="@selected($booking->source)">
                                                    {{ $booking->user->name }} ({{ $booking->user->id }})
                                                </span>
                                            </td>
                                            <td>{{ $booking->user->status->value }}</td>
                                            <td class="no-border">&nbsp;</td>
                                            <td class="no-border">
                                                <input type="text" readonly="true">
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>{{ $id }}</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td class="no-border">&nbsp;</td>
                                            <td class="no-border">
                                                <input type="text" readonly="true">
                                            </td>
                                        </tr>
                                    @endif
                                @endfor
                                <tr>
                                    <td style="background: #f6f8cd" colspan="3">
                                        <strong>Standby</strong>
                                    </td>
                                    <td class="no-border">&nbsp;</td>
                                    <td class="no-border">&nbsp;</td>
                                </tr>
                                @php
                                    $stand_by_bookings = $classSchedule->stand_by_bookings;
                                @endphp
                                @for ($id = 1; $id <= 5; $id++)
                                    @php
                                        $booking = isset($stand_by_bookings[$id - 1]) ? $stand_by_bookings[$id - 1] : false;
                                    @endphp
                                    @if ($booking)
                                        <tr>
                                            <td>S{{ $id }}</td>
                                            <td>
                                                <span class="@selected($booking->source)">
                                                    {{ $booking->user->name }} ({{ $booking->user->id }})
                                                </span>
                                            </td>
                                            <td>{{ $booking->user->status->value }}</td>
                                            <td class="no-border">&nbsp;</td>
                                            <td class="no-border">
                                                <input type="text" readonly="true">
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>S{{ $id }}</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td class="no-border">&nbsp;</td>
                                            <td class="no-border">
                                                <input type="text" readonly="true">
                                            </td>
                                        </tr>
                                    @endif
                                @endfor
                            </tbody>
                        </table>
                    </td>
                    <td style="width:120px; padding: 15px" rowspan="4" valign="top">
                        <div style="position: relative">
                            NitroFIT28 Sign off <input id="signed-off" type="checkbox" disabled="disabled"
                                @if ($classSchedule->has_sign_off) checked="checked" @endif />
                        </div>
                        @if ($classSchedule->has_sign_off)
                            <div>{{ $classSchedule->admin->name }}<br>{{ $classSchedule->sign_off_at }}</div>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 15px;" colspan="2">
                        <div>Note for INSTRUCTOR</div>
                        <textarea rows="4"></textarea>
                    </td>
                </tr>
            </tbody>
        </table>
        <table cellspacing="0">
            <tbody>
                <tr>
                    <td>Print Instructor Name:</td>
                    <td style="width: 110px;">
                        <input type="text">
                    </td>
                </tr>
                <tr>
                    <td>Sign Instructor:</td>
                    <td style="width: 110px;">
                        <input type="text">
                    </td>
                </tr>
                <tr>
                    <td>Date:</td>
                    <td style="width: 110px;">
                        <input type="text">
                    </td>
                </tr>
                <tr>
                    <td>NitroFIT28 Staff Sign off:</td>
                    <td style="width: 110px;">
                        <input type="text">
                    </td>
                </tr>
            </tbody>
        </table>
    </main>
</body>

</html>
