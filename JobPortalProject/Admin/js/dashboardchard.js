const ctx = document.getElementById('dashboardchart');

new Chart(ctx, {
  type: 'polarArea',
  data: {
    labels: ['Total Users','Job Seekers', 'Employers', 'Job Listings' ],
    datasets: [{
      label: '',
      data: [totalUsers, jobseekerCount, employerCount, joblistingsCount],
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    },
});