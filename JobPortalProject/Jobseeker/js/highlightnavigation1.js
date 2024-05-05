// Function to highlight the active navigation item
function highlightActiveNavigationItem() {
    // Get the stored active link from local storage
    const activeNavItem = localStorage.getItem('activeNavItem');
    const list = document.querySelectorAll('.navigation li');

    if (activeNavItem) {
        // Remove the 'hovered' class from all items
        list.forEach((item) => item.classList.remove('hovered'));

        // Add the 'hovered' class to the active item
        document.querySelector(`.navigation a[href="${activeNavItem}"]`).parentElement.classList.add('hovered');
    }
}

// Add a click event listener to all navigation links
const links = document.querySelectorAll('.navigation a');
links.forEach((link) => {
    link.addEventListener('click', function (event) {
        // Get the clicked link's href attribute
        const clickedLink = event.currentTarget.getAttribute('href');

        // Store the clicked link in local storage
        localStorage.setItem('activeNavItem', clickedLink);
    });
});

// Call the function to highlight the active navigation item
highlightActiveNavigationItem();
