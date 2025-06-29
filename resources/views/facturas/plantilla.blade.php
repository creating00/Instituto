<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Factura</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        h2 {
            margin-top: 0;
        }
    </style>
</head>

<body>
    <h2>Factura</h2>
    <p><strong>Cliente:</strong> {{ $cliente }}</p>
    <p><strong>Fecha:</strong> {{ $fecha }}</p>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach ($items as $item)
                @php
                    $subtotal = $item['cantidad'] * $item['precio'];
                    $total += $subtotal;
                @endphp
                <tr>
                    <td>{{ $item['producto'] }}</td>
                    <td>{{ $item['cantidad'] }}</td>
                    <td>${{ number_format($item['precio'], 2) }}</td>
                    <td>${{ number_format($subtotal, 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3"><strong>Total:</strong></td>
                <td><strong>${{ number_format($total, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>
</body>

</html>