<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QrCode</title>
    <style>
        .qr-container {
            text-align: center; /* Centers the text */
        }
        
        .qr-code {
            display: inline-flex; /* Use inline-flex to center the QR code without affecting the full page layout */
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>
    <x-navbar></x-navbar>
    <div class="visible-print text-center qr-container">
        <p>Scan QR Code to view ticket</p>
        <div class="qr-code">
            @foreach ($routes as $route)
                <div class="route-container">
                    <div class="ticket-details">
                        <p><strong>Movie:</strong> {{ $route['Movie'] }}</p>
                        <p><strong>Time:</strong> {{ $route['MovieTime'] }}</p>
                        <p><strong>Location:</strong> {{ $route['Location'] }}</p>
                        <p><strong>Hall:</strong> {{ $route['Hall'] }}</p>
                        <p><strong>Seat:</strong> {{ $route['Seat'] }}</p>
                    </div>
                    <div class="qr-code">
                        {!! QrCode::size(100)->generate(route('qr.moviecustomerscanned', $route)); !!}
                    </div>
                </div>
            @endforeach

        </div>
    </div>
    <style>
        .route-container{
            border: 1px solid black;
            padding: 10px;
            margin: 5px;
        }
        .qr-code{
            display: flex;
            flex-direction: column;
        }

        .route-container{
            display: flex;
        }
    </style>
</body>
</html>