<!DOCTYPE html>
<html>
<head>
    <title>Order Completed</title>
</head>
<body>
    <h2>Hello, {{ $userName }}</h2>

    @if($order->delivery_type === 'delivery')
        <p>Your order containing the following products has been delivered:</p>
    @else
        <p>Your order containing the following products is ready for pickup. Please claim your order:</p>
    @endif

    <ul>
        @foreach($productList as $product)
            <li>{{ $product }}</li>
        @endforeach
    </ul>

    <p>Please prepare the exact amount of <strong>{{ $totalAmount }}</strong>.</p>
    <p>Thank you for choosing us!</p>
</body>
</html>
