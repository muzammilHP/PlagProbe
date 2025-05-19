<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Class Plagiarism MDS Visualization</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap (optional) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    canvas {
        max-height: 500px;
        max-width: 100%;
    }
</style>
</head>
<body class="bg-light">

<div class="container py-5">
    <h2 class="mb-4 text-center">MDS-Based Similarity Plot of Class Submissions</h2>

    <canvas id="mdsChart" height="400"></canvas>
</div>

<script>
    const rawData = @json($clusters);

    // Generate a list of distinct colors for clusters
    const clusterColors = [
        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
        '#FF9F40', '#8B0000', '#2E8B57', '#1E90FF', '#A0522D'
    ];

    // Group data by clusters
    const groupedByCluster = {};
    rawData.forEach(item => {
        if (!groupedByCluster[item.cluster]) {
            groupedByCluster[item.cluster] = [];
        }
        groupedByCluster[item.cluster].push({
            x: item.x,
            y: item.y,
            student: item.student_name,
            file: item.file_name
        });
    });

    // Create datasets per cluster
    const datasets = Object.keys(groupedByCluster).map((cluster, index) => ({
        label: `Cluster ${cluster}`,
        data: groupedByCluster[cluster],
        backgroundColor: clusterColors[index % clusterColors.length],
        pointRadius: 6
    }));

    const ctx = document.getElementById('mdsChart').getContext('2d');

    new Chart(ctx, {
        type: 'scatter',
        data: {
            datasets: datasets
        },
        options: {
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const d = context.raw;
                            return `Student: ${d.student}\nFile: ${d.file}`;
                        }
                    }
                },
                title: {
                    display: true,
                    text: '2D Similarity Plot of Submissions (via MDS & KMeans Clustering)'
                },
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'X Coordinate (Similarity)'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Y Coordinate (Similarity)'
                    }
                }
            }
        }
    });
</script>

</body>
</html>
