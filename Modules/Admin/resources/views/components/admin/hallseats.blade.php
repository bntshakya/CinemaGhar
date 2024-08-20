<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hall Seats Sales Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->
</head>

<body>
    <div class="hall-seats-container chart-container">
        <p>Hall Seats Sales Chart</p>
        <select id="locationDropdown"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
           
            @foreach($locations as $location)
                <option value="{{ $location->location }}">{{ $location->location }}</option>
            @endforeach
        </select>

        <canvas id="hallseatsChart"></canvas>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('hallseatsChart').getContext('2d');
            const dropdown = document.getElementById('locationDropdown');
            let ticketsChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['halls'], // Empty initially
                    datasets: [
                        @foreach ($movienames as $moviename)
                            {
                                label: '{{$moviename}}',
                                data: [],
                                borderWidth: 1,
                            },
                        @endforeach
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            dropdown.addEventListener('change', function () {
                const selectedLocation = dropdown.value;

                // Perform an AJAX request to get data for the selected location
                $.ajax({
                    url: '/get-hall-seats-data', // Replace with your endpoint
                    type: 'GET',
                    data: {
                        location: selectedLocation
                    },
                    success: function (response) {
                        // Update the chart with the response data
                        ticketsChart.data.labels = response.labels;
                        for (let i = 0; i < ticketsChart.data.datasets.length; i++) {
                            ticketsChart.data.datasets[i].data = response.data[i];
                        }
                        ticketsChart.update();
                    },
                    error: function (error) {
                        console.error('Error fetching data:', error);
                    }
                });
            });
        });
    </script>
</body>

</html>