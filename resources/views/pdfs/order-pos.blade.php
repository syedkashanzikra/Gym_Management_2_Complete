<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{{ $id }}</title>
    @include('includes.pdf-style')
    <style>
        body {
            font-size: 13px;
            font-family: Helvetica, sans-serif;
            margin: 0px;
        }

        @page {
            margin: 0px;
        }
    </style>
</head>

<body>
    <main>
        <table style="margin-bottom: 15px" cellspacing="0">
            <tr>
                <td class="text-center text-bold" style="font-size:24px; text-transform: uppercase;">
                    <img width="100px" src="{{ $logo ?? asset('images/logo.png') }}" alt="{{ config('app.name') }}">
                </td>
            </tr>
            <tr>
                <td class="text-center" style="font-size: 17px;">
                    <div style="padding: 0 35px">{{ $location }}</div>
                </td>
            </tr>
            <tr>
                <td class="text-center" style="font-size: 15px; padding-top: 15px;">
                    --------------------- RETAIL INVOICE ---------------------
                </td>
            </tr>
        </table>
        <table style="margin-bottom: 20px" cellspacing="0">
            <tr>
                <td>Invoice No: {{ $id }}</td>
                <td>Customer: {{ $customer_name }}</td>
            </tr>
            <tr>
                <td>Date: <span class="text-uc">{{ $created_at }}</span></td>
                <td>Phone: {{ $phone_number ?? '0000000000' }}</td>
            </tr>
        </table>
        <table style="margin: 10px 0" cellspacing="0" class="table">
            <tbody>
                <tr>
                    <td class="dashed-bt text-bold">SL</td>
                    <td class="dashed-bt text-bold">ITEM</td>
                    <td class="dashed-bt text-bold text-center">PRICE</td>
                    <td class="dashed-bt text-bold text-center">QTY</td>
                    <td class="dashed-bt text-bold text-right">TOTAL</td>
                </tr>
                @foreach ($line_items as $index => $item)
                    <tr>
                        <td>
                            {{ $index + 1 }}
                        </td>
                        <td>
                            <div>
                                {{ $item->title }}
                                @if ($item->variant_title != 'Default')
                                    <br>
                                    {{ $item->variant_title }}
                                @endif
                            </div>
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
                    <td colspan="2" class="dashed-top"></td>
                    <td colspan="2" class="dashed-top text-right">Subtotal:</td>
                    <td colspan="1" class="dashed-top text-right">{{ $sub_total }}</td>
                </tr>
                <tr>
                    <td colspan="2" class="text-right"></td>
                    <td colspan="2" class="text-right">Discount:</td>
                    <td colspan="1" class="text-right">-{{ $discount_total }}</td>
                </tr>
                <tr>
                    <td colspan="2" class="text-right"></td>
                    <td colspan="2" class="text-right">Tax:</td>
                    <td colspan="1" class="text-right">+{{ $tax_total }}</td>
                </tr>
                <tr>
                    <td colspan="2" class="text-right"></td>
                    <td colspan="2" class="text-right">Grand Total:</td>
                    <td colspan="1" class="text-right">{{ $grand_total }}</td>
                </tr>
                <tr>
                    <td colspan="2" class="text-right"></td>
                    <td colspan="2" class="text-right dashed-top">Cash Paid:</td>
                    <td colspan="1" class="text-right dashed-top">{{ $paid_total }}</td>
                </tr>
                <tr>
                    <td colspan="2" class="text-right"></td>
                    <td colspan="2" class="text-right">Due Amount:</td>
                    <td colspan="1" class="text-right">{{ $due_amount }}</td>
                </tr>
                <tr>
                    <td colspan="2" class="text-right"></td>
                    <td colspan="3" class="dashed-top"></td>
                </tr>
            </tbody>
        </table>
        <div class="text-center" style="margin: 20px 0">Thank you for shopping with us!</div>
    </main>
</body>

</html>
