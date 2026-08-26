<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order Email</title>
</head>

<body style="font-family:Arial, Helvetica, sans-serif; font-size:14px;">

    @if ($mailData['userType'] == 'customer')
        <h2>Thanks for your order!!</h2>
        <h3>Your order Id is: #{{ $mailData['order']->id }}</h2>
    @else
        <h2>You have received an order:</h2>
        <h3>Order Id: #{{ $mailData['order']->id }}</h2>
    @endif

    <div style="margin-bottom: 10px;">
        <span style="margin-bottom: 5px">Shipping Address:</span><br />
        <b>{{ $mailData['order']->first_name.' '.$mailData['order']->last_name.'' }}</b><br />
        {{ $mailData['order']->address }}<br>
        {{ $mailData['order']->city }}, {{ $mailData['order']->zip }}, {{ getStateInfo($mailData['order']->state_id)->name }}.<br>
        Phone: {{ $mailData['order']->mobile }}<br>
        Email: {{ $mailData['order']->email }}
    </div>

    <table cellpadding="3" cellspacing="3" border="0" width="700">
        <thead>
            <tr style="background: #ccc;">
                <th>Product</th>
                <th style="text-align: right">Price</th>
                <th style="text-align: center">Qty</th>
                <th style="text-align: right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mailData['order']->items as $item)

            @endforeach
            <tr>
                <td>{{ $item->name }}</td>
                <td style="text-align: right">₹ {{ number_format($item->price,2) }}</td>
                <td style="text-align: center">{{ $item->qty }}</td>
                <td style="text-align: right">₹ {{ number_format($item->total,2) }}</td>
            </tr>

            <tr>
                <td colspan="3" style="text-align: right" >Subtotal:</td>
                <td style="text-align: right">₹ {{ number_format($mailData['order']->subtotal,2) }}</td>
            </tr>

            <tr>
                <td colspan="3" style="text-align: right">Discount: {{ (!empty($mailData['order']->coupon_code)) ? '('.$mailData['order']->coupon_code.')' : '' }}</td>
                <td style="text-align: right">₹ {{ number_format($mailData['order']->discount,2) }}</td>
            </tr>

            <tr>
                <td colspan="3" style="text-align: right">Shipping:</td>
                <td style="text-align: right">₹ {{ number_format($mailData['order']->shipping,2) }}</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right">Grand Total:</td>
                <th style="text-align: right">₹ {{ number_format($mailData['order']->grandtotal,2) }}</th>
            </tr>
        </tbody>
    </table>

</body>
</html>
