<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    @vite('resources/css/app.css')
</head>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
<body>
    <div class="chart-container">
        <p>Movie sales Chart</p>
        <canvas id="myChart"></canvas>
    </div>   
    <x-admin::admin.hallseats :tickets="$tickets" :locations="$locations" :movienames="$movienames"/> 
</body>
<script>
    const label = @json($dates);
    document.addEventListener('DOMContentLoaded',function(){
        var ctx = document.getElementById('myChart').getContext('2d');
        var ticketsChart = new Chart(ctx,{
            type:'bar',
            data:{
                labels:label,
                datasets:[
                    @foreach ($b as $k=>$c)
                    {
                        label:'{{$movienames[$k]}}',
                        data:{{json_encode($c)}},
                        borderWidth:1,
        
                    },
                    @endforeach
            ]
            }
        })
    })
</script>
</html>