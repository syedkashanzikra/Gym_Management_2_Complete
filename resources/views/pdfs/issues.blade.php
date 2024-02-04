<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        Issues
    </title>
    <style>
        body {
            font-size: 12px;
            font-family: Helvetica, sans-serif;
        }

        table {
            width: 100%;
        }

        .table td,
        .table th {
            padding: 2px 5px;
        }

        .table th {
            background: lightgray
        }

        .bg-grey {
            background: rgb(226, 226, 226)
        }

        .table tbody td.border,
        .border thead th,
        .border tbody td {
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

        .text-left {
            text-align: left;
        }

        .no-border {
            border: none !important;
        }
    </style>
</head>

<body>
    <main>
        <table class="table border" cellspacing="0">
            <thead>
                <tr>
                    <th align="left">Issue</th>
                    <th align="left">Created at</th>
                    <th align="left">From</th>
                    <th align="left">To</th>
                    <th align="left">Archived</th>
                    <th align="left">Status</th>
                    <th align="left">Last update</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($issues as $row)
                    <tr>
                        <td class="q-td text-left">
                            [{{ $row->id }}] {{ $row->subject }}
                        </td>
                        <td class="q-td text-left">
                            {{ $row->created_at->format('d M, Y \a\t h:i a') }}
                        </td>
                        <td class="q-td text-left">
                            {{ optional($row->user)->name }} ({{ optional($row->user)->email }})
                        </td>
                        <td class="q-td text-left">
                            {{ $row->getUsers() }}
                        </td>
                        <td class="q-td text-left">
                            {{ $row->is_archived ? 'Yes' : 'No' }}
                        </td>
                        <td class="q-td text-left">
                            {{ $row->status->value }}
                        </td>
                        <td class="q-td text-left">
                            @if ($row->last_reply && $row->last_reply->user)
                                {{ $row->last_reply->user->name }} on
                                {{ $row->last_reply->created_at->format('d M, Y \a\t h:i a') }}
                            @elseif ($row->last_reply)
                                System on {{ $row->last_reply->created_at->format('d M, Y \a\t h:i a') }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
</body>

</html>
