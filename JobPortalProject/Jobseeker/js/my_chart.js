const ctx = document.getElementById('myChart').getContext('2d');

// Initial data for the donut chart
const initialData = {
  labels: ['Rating', ''],
  datasets: [{
    label: 'Rating',
    data: [0, 10],
    backgroundColor: [
      'rgba(21, 41, 53, 1)',
      'rgba(255, 255, 255, 1)',
    ],
  }],
};

// Create the donut chart
const chart = new Chart(ctx, {
  type: 'doughnut',
  data: initialData,
  options: {
    responsive: true,
    legend: {
      display: false,
    },
    cutout: '75%',
  },
});

// Function to calculate the rating based on the number of words
function calculateRating(text) {
  // Assuming you want to give a rating out of 10 based on the number of words
  const totalWords = text.split(/\s+/).length; // Split text into words
  const maxWords = 200; // Define the maximum number of words for a full rating
  const rating = (totalWords / maxWords) * 10;
  return Math.min(10, rating); // Ensure the rating doesn't exceed 10
}

// Function to update the Rating value and the chart
function updateRatingValue(newRating) {
  // Update the HTML content to display the new Rating value
  document.getElementById('ratingValue').textContent = `Rating = ${newRating.toFixed(0)}`;

  // Update the chart data with the new Rating value
  chart.data.datasets[0].data = [newRating, 10 - newRating];
  chart.update(); // Update the chart to reflect the new data
}

// Function to handle file upload and calculate the rating
function handleFileUpload(file) {
  const reader = new FileReader();
  reader.onload = function (event) {
    const resumeText = event.target.result; // Text content of the resume
    const rating = calculateRating(resumeText);
    updateRatingValue(rating);
  };

  reader.readAsText(file);
}

// Event listener for file input change
document.getElementById('resumeFile').addEventListener('change', function (event) {
  const file = event.target.files[0];
  if (file) {
    handleFileUpload(file);
  }
});
