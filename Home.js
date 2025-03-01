// Add interactivity for navigation popup (if needed)
// document.querySelectorAll('.nav-link').forEach(link => {
//     link.addEventListener('click', e => {
//         e.preventDefault();
//         alert(`You selected: ${link.textContent}`);
//     });
// });

function showPopup(event) {
    event.preventDefault();  // Prevent default link behavior
    document.getElementById('socialPopup').style.display = 'flex';  // Show the popup
  }
  
  function closePopup() {
    document.getElementById('socialPopup').style.display = 'none';  // Hide the popup
  }
  
