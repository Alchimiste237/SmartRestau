<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $restaurant->name }} QR Codes</title>
    <style>
        body { font-family: sans-serif; }
        .grid { width: 100%; border-collapse: collapse; }
        .card { 
            width: 30%; 
            padding: 20px; 
            border: 1px solid #eee; 
            text-align: center; 
            display: inline-block;
            margin: 1%;
            vertical-align: top;
        }
        .restaurant-name { font-size: 14px; color: #666; margin-bottom: 5px; }
        .table-number { font-size: 24px; font-weight: bold; margin-bottom: 10px; color: #E85D24; }
        .qr-image { width: 150px; height: 150px; }
        .instruction { font-size: 10px; color: #999; margin-top: 10px; }
    </style>
</head>
<body>
    <h1 style="text-align: center; color: #E85D24;">{{ $restaurant->name }}</h1>
    <p style="text-align: center; margin-bottom: 40px;">Scan the QR codes below to view our digital menu and place your order.</p>

    <div class="grid">
        @foreach($tableData as $table)
            <div class="card">
                <div class="restaurant-name">{{ $restaurant->name }}</div>
                <div class="table-number">{{ $table['number'] }}</div>
                <img src="data:image/svg+xml;base64,{{ $table['qr_code'] }}" class="qr-image">
                <div class="instruction">Scan to order</div>
            </div>
        @endforeach
    </div>
</body>
</html>
