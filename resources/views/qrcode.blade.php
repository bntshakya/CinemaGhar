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
            {!! QrCode::size(100)->generate($route); !!}
        </div>
    </div>
</body>
</html>