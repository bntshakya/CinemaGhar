<!DOCTYPE html>
<html>

<head>
    <title>Listen for Alerts</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/js/app.js')
</head>

<body>
    <h1>Listening for alerts...</h1>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window.Echo.channel('alerts')
                .listen('AlertTriggered', () => {
                    alert('An alert was triggered!');
                });
        });
    </script>
</body>

</html>