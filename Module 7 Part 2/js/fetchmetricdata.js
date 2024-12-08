async function fetchMetricData() {
    try {
        const response = await fetch('../fetchmetrics.php');
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching metric data:', error);
    }
}

async function renderChart() {
    try {
        const metricData = await fetchMetricData();

        if (!metricData || metricData.length === 0) {
            console.warn('No metric data available to display.');
            return;
        }

        // Process data
        const labels = metricData.map(
            item =>
                `${item.page_url} (${new Date(item.timestamp).toLocaleDateString()})`
        );
        const pageViewValues = metricData.map(item => item.page_view_count);
        const sessionDurationValues = metricData.map(item => item.session_duration);

        // Create the chart
        const ctx = document.getElementById('metricChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Page Views',
                        data: pageViewValues,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                    },
                    {
                        label: 'Session Duration (seconds)',
                        data: sessionDurationValues,
                        backgroundColor: 'rgba(255, 159, 64, 0.2)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Values',
                        },
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Page URL and Date',
                        },
                    },
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const label = context.dataset.label || '';
                                const value = context.raw;
                                return `${label}: ${value}`;
                            },
                        },
                    },
                },
            },
        });
    } catch (error) {
        console.error('Error rendering the chart:', error);
    }
}

renderChart();