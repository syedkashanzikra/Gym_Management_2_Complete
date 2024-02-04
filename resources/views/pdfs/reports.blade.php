<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        Reports
    </title>
    @include('includes.pdf-style')
</head>

<body>
    <main>
        <table class="table table-bordered" cellspacing="0">
            <thead>
                <tr>
                    @foreach ($columns as $col)
                        <th align="{{ $col['align'] }}">{{ $col['label'] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $row)
                    <tr>
                        @foreach ($columns as $col)
                            <td class="q-td text-{{ $col['align'] }}">
                                @isset($row[$col['field']])
                                    {{ $row[$col['field']] }}
                                @endisset
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
</body>

</html>
