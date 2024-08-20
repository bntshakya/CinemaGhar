@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Revenue</h1>
@stop

@section('content')
<div class="chart-container">
    <canvas id="myChart"></canvas>
    <canvas id="detailedchart"></canvas>
</div>
@endsection

@section('meta_tags')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>   
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.0.2"></script>

@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('myChart');
        const options = {
            plugins: {
                annotation: {
                    annotations: {
                        line1: {
                            type: 'line',
                            yMin: {{$cost}},
                            yMax: {{$cost}},
                            borderColor: 'rgb(255, 99, 132)',
                            borderWidth: 2,
                            fill: false
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        };
        new Chart(ctx, {
            type: 'line', // Assuming a line chart
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Revenue',
                    data: @json($revenue), 
                    borderColor: "#007bff",
                    borderWidth: 1,
                    fill: false,
                },
                {
                    type: 'line',
                    label: 'Net Revenue',
                    data: @json($NetRevenue)
                }]
            },
            options: options
        });
    

        const detailedChartCtx = document.getElementById('detailedchart');
        new Chart(detailedChartCtx, {
            data: {
                labels: @json($labels),
                datasets: @json($datasets)
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endsection

@section('css')
<style>
    .chart-container {
        width: 50%;
        /* Adjust this value to make the chart smaller or larger */
        margin: 0 auto;
        /* This centers the div horizontally */
    }

    canvas {
        max-width: 100%;
        /* This ensures the canvas is responsive and fits within the container */
        height: auto;
        /* Adjust the height as needed */
    }
</style>
@endsection