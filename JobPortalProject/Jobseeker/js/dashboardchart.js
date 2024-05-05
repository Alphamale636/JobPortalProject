const ctx = document.getElementById('dashboardchart');

new Chart(ctx, {
  type: 'polarArea',
  data: {
    labels: ['Applied Jobs','Selected Jobs', 'Pending Jobs', 'Rejected Jobs' ],
    datasets: [{
      label: '',
      data: [AppledJobs, selectedCount, pendingCount, rejectedCount],
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    },
});