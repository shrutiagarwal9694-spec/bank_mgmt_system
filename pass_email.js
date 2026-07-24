function get_email() {
    const input = document.getElementById('UN').value;
    if (input) {
        const email = input.value.trim();
        if (email) {
            sessionStorage.setItem('email', email);
        } else {
            alert('Please enter an email.');
        }
    } else {
        console.error("Input element with ID 'UN' not found.");
    }
}

window.addEventListener('load', () => {
    const emailField = document.getElementById("email").value;
    if (emailField) {
        const storedEmail = sessionStorage.getItem('email');
        if (storedEmail) {
            emailField.value = storedEmail;
        }
    } else {
        console.error("Input element with ID 'email' not found.");
    }
});
