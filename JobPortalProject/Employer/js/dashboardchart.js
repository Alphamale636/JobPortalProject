const ctx = document.getElementById('dashboardchart');

new Chart(ctx, {
  type: 'polarArea',
  data: {
    labels: ['Active Job Postings', 'Total Applicants', 'Selected Candidates', 'Rejected Candidates'],
    datasets: [{
      label: '',
      data: [activeJobPostings, totalApplicants, selectedApplicants, rejectedApplicants],
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    },
});