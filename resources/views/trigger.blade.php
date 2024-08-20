<!DOCTYPE html>
<html>
<head>
    <title>Trigger Alert</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>
<body>
    <button onclick="triggerAlert()">Trigger Alert</button>

    <script>
        function triggerAlert() {
            fetch('/trigger-alert');
        }
    </script>
</body>
</html>
