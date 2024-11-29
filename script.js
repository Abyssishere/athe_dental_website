// Smooth Scroll for Navigation
document.querySelectorAll('nav ul li a').forEach(link => {
    link.addEventListener('click', (event) => {
        event.preventDefault();
 	console.log("Link clicked: " + link.href); 
        window.location.href = link.href;
    });
});

// Form Validation
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
        const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
        let valid = true;
        inputs.forEach(input => {
            if (!input.value.trim()) {
                valid = false;
                input.style.border = '2px solid red';
                input.addEventListener('input', () => input.style.border = '');
            }
        });
        if (!valid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });
});
