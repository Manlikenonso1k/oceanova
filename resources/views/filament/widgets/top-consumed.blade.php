<div>
    <canvas id="topConsumedChart" height="120"></canvas>
</div>

<script>
    (function() {
        const labels = @json($this->labels);
        const data = @json($this->values);

        const ctx = document.getElementById('topConsumedChart').getContext('2d');
        // Load Chart.js dynamically if not present
        (function loadChart(cb){
            if (typeof Chart !== 'undefined') return cb();
            const s = document.createElement('script');
            s.src = 'https://cdn.jsdelivr.net/npm/chart.js';
            s.onload = cb;
            document.head.appendChild(s);
        })(function(){
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Sales',
                        data: data,
                        backgroundColor: 'rgba(59,130,246,0.7)',
                        borderColor: 'rgba(59,130,246,1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        });
    })();
</script>
