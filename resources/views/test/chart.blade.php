<html>
<head>
</head>
<body>
<div style="display: flex;justify-content: space-around;margin-top: 100px;">
    <div style="width: 500px; height: 400px;">
        <canvas id="myChart" width="500" height="400"></canvas>
    </div>

    <div style="width: 500px; height: 400px;">
        <canvas id="myChart2" width="500" height="400"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('myChart');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
            datasets: [{
                label: '# of Votes',
                data: [12, 19, 3, 5, 2, 3],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    const ctx2 = document.getElementById('myChart2');

    new Chart(ctx2, {
        type: 'line',
        data: {
            datasets: [{
                label: 'Dataset 1',
                data: [
                    {x: '01-12-2022', y: 20},
                    {x: '02-12-2022', y: 10},
                    {x: '03-12-2022', y: 15}
                ]
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            title: {
                display: true,
                text: 'Chart.js Line Chart'
            }
        }
    });
</script>
</body>
</html>
