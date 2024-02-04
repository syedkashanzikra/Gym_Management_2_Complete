<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{{ $id }}</title>
    @include('includes.pdf-style')
</head>

<body>
    <main>
        <table style="margin-bottom: 20px" cellspacing="0">
            <tr>
                <td style="font-size: 23px; text-transform: uppercase; width: 70%">
                    <img width="100px" src="{{ $logo ?? asset('images/logo.png') }}" alt="{{ config('app.name') }}">
                </td>
                <td class="text-right" style="width: 30%">
                    <div>ORDER {{ $id }}</div>
                    <div>{{ $created_at }}</div>
                </td>
            </tr>
        </table>
        <table cellspacing="0" style="margin-bottom: 20px">
            <tr>
                <td style="width: 150px;">
                    <div class="text-bold" style="margin-bottom: 10px">BILLING ADDRESS</div>
                    @if ($billing_address)
                        <div>{{ $billing_address }}</div>
                    @else
                        <div class="text-grey">No billing info provided</div>
                    @endif
                </td>
                <td></td>
            </tr>
        </table>
        <table style="margin: 10px 0;" cellspacing="0" class="table">
            <tbody>
                <tr>
                    <td colspan="2" class="text-bold">ITEMS</td>
                    <td class="text-bold text-center">PRICE</td>
                    <td class="text-bold text-center">QTY</td>
                    <td class="text-bold text-right">TOTAL</td>
                </tr>
                @foreach ($line_items as $item)
                    <tr>
                        <td style="width: 40px">
                            @php
                                $thumbnail = $item->thumbnail ?? optional($item->product)->thumbnail;
                            @endphp
                            <img class="thumbnail" src="{{ $thumbnail->url ?? asset('images/placeholder.jpg') }}">
                        </td>
                        <td>
                            <p>
                                {{ $item->title }}
                                @if ($item->variant_title != 'Default')
                                    <br>
                                    {{ $item->variant_title }}
                                @endif
                            </p>
                        </td>
                        <td class="text-center">
                            {{ format_amount($item->price * 100) }}
                        </td>
                        <td class="text-center">
                            {{ $item->quantity }}
                        </td>
                        <td class="text-right">
                            {{ format_amount($item->total * 100) }}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3"></td>
                    <td colspan="1" class="border-top text-right">Subtotal:</td>
                    <td colspan="1" class="border-top text-right">{{ $sub_total }}</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td colspan="1" class="text-right">Discount:</td>
                    <td colspan="1" class="text-right">-{{ $discount_total }}</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td colspan="1" class="text-right">Tax:</td>
                    <td colspan="1" class="text-right">+{{ $tax_total }}</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td colspan="1" class="border-top text-right">Grand Total:</td>
                    <td colspan="1" class="border-top text-right">{{ $grand_total }}</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td colspan="1" class="text-right ">Cash Paid:</td>
                    <td colspan="1" class="text-right ">{{ $paid_total }}</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td colspan="1" class="text-right">Due Amount:</td>
                    <td colspan="1" class="text-right">{{ $due_amount }}</td>
                </tr>
            </tbody>
        </table>
        <div style=" margin-bottom: 40px"></div>
        <div class="text-center" style="margin: 20px 0">Thank you for shopping with us!</div>
        <div class="text-center text-bold">{{ config('app.name') }}</div>
        <div class="text-center">{{ $location }}</div>
    </main>
</body>

</html>
