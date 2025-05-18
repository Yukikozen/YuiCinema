<?php
session_start();
include_once '../conn/config.php';

if (empty($_SESSION["admin_username"])) {
    header("Location:index.php");
} else {
    include_once("admin_header.php");
?>
    <section>
        <div class="continer"><!-- Fixed spelling from "continer" -->
            <div class="row">
                <div class="col-md-2" style="background-color:maroon;">
                    <?php include('admin_sidenavbar.php'); ?>
                </div>
                <div class="col-md-10">
                    <h5 class="text-center mt-2" style="color:maroon;">Admin Dashboard</h5>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h4 class="text-center">Movie Viewer Count (Live)</h4>
                            <div style="position: relative; height:400px;">
                                <canvas id="viewChart"></canvas>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4 class="text-center">Movie Genre Views (Live)</h4>
                            <div style="position: relative; height:400px;">
                                <canvas id="genreChart"></canvas>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <script>
        const ctx = document.getElementById('viewChart').getContext('2d');
        let chart;

        function fetchDataAndUpdateChart() {
            fetch('get_movie_viewers.php')
                .then(response => response.json())
                .then(data => {
                    const labels = data.map(d => d.movie);
                    const viewerCounts = data.map(d => d.viewers);

                    if (chart) {
                        chart.data.labels = labels;
                        chart.data.datasets[0].data = viewerCounts;
                        chart.update();
                    } else {
                        chart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Viewer Count',
                                    data: viewerCounts,
                                    backgroundColor: 'rgba(128,0,0,0.6)',
                                    borderColor: 'maroon',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    }
                });
        }

        fetchDataAndUpdateChart();
        setInterval(fetchDataAndUpdateChart, 5000);
    </script>

    <script>
        const genreCtx = document.getElementById('genreChart').getContext('2d');
        let genreChart;

        function fetchGenreDataAndUpdateChart() {
            fetch('get_genre_views.php')
                .then(response => response.json())
                .then(data => {
                    const labels = data.map(d => d.genre);
                    const views = data.map(d => d.views);

                    const backgroundColors = [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                        '#FF9F40', '#C9CBCF', '#7DCEA0', '#F1948A', '#5DADE2'
                    ];

                    if (genreChart) {
                        genreChart.data.labels = labels;
                        genreChart.data.datasets[0].data = views;
                        genreChart.update();
                    } else {
                        genreChart = new Chart(genreCtx, {
                            type: 'pie',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Genre Views',
                                    data: views,
                                    backgroundColor: backgroundColors,
                                    borderColor: '#fff',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'right',
                                    },
                                    tooltip: {
                                        enabled: true
                                    }
                                }
                            }
                        });
                    }
                })
                .catch(err => {
                    console.error('Error fetching genre views:', err);
                });
        }

        fetchGenreDataAndUpdateChart();
        setInterval(fetchGenreDataAndUpdateChart, 5000);
    </script>

<?php
    include_once("admin_footer.php");
}
?>
