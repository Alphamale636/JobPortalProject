const overlay = document.getElementById('overlay');
const customConfirm = document.getElementById('custom-confirm');
const yesButton = document.getElementById('yes-button');
const noButton = document.getElementById('no-button');

function showDialog() {
    overlay.style.display = 'block';
    customConfirm.style.display = 'block';
}

function hideDialog() {
    overlay.style.display = 'none';
    customConfirm.style.display = 'none';
}

function ShowCustomConfirm() {
    showDialog();

    yesButton.addEventListener('click', function() {
        logoutUser();
        hideDialog();
    });

    noButton.addEventListener('click', function() {
        hideDialog();
    });
}
function logoutUser()
 {
    window.location.href = 'logout.php';
}