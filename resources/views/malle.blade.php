<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Meta tags, CSRF token, title, stylesheets, etc. -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Additional meta tags, stylesheets, etc. -->

    <!-- Scripts -->
    @vite('resources/js/app.js')

</head>

<body>
    <div id="app">
        <!-- Navigation, headers, etc. -->

        <main class="py-4">
            <div class="container">
                <div class="card">
                    <div class="card-header" data-malleable="true">Manage Users</div>
                    <div class="card-body">
                        <p data-malleable="true">Click me to edit</p>
                    </div>

                    <table style="width:100%" border="4">
                        <tr>
                            <th data-malleable="true">Company</th>
                            <th data-malleable="true">Contact</th>
                            <th data-malleable="true">Country</th>
                        </tr>
                        <tr>
                            <td>Alfreds Futterkiste</td>
                            <td>Maria Anders</td>
                            <td>Germany</td>
                        </tr>

                    </table>


                </div>
            </div>
        </main>
    </div>
</body>

</html>