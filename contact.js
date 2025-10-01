document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('contact-form');
    const nameField = document.getElementById('Name');
    const emailField = document.getElementById('Email');
    const messageField = document.getElementById('Message');
    const thankYouMessage = document.createElement('div');
    thankYouMessage.className = 'thank-you-message';
    document.body.appendChild(thankYouMessage);

    form.addEventListener('submit', function(event) {
        let isValid = true;

        // Clear previous error messages
        document.querySelectorAll('.error-message').forEach(el => el.remove());

        // Validate Name
        if (nameField.value.trim() === "") {
            showError(nameField, "Name is required.");
            isValid = false;
        }

        // Validate Email
        if (emailField.value.trim() === "") {
            showError(emailField, "Email is required.");
            isValid = false;
        } else if (!validateEmail(emailField.value)) {
            showError(emailField, "Please enter a valid email address.");
            isValid = false;
        }

        // Validate Message
        if (messageField.value.trim() === "") {
            showError(messageField, "Message is required.");
            isValid = false;
        }

        if (!isValid) {
            event.preventDefault();
        } else {
            event.preventDefault(); // Prevent form submission for showing thank you message

            // Display thank you message with user's name
            thankYouMessage.textContent = `Thank you, ${nameField.value.trim()}, for your message!`;
            thankYouMessage.style.display = 'block';

            // Optionally, you can hide the form after submission
            form.style.display = 'none';
        }
    });

    function showError(input, message) {
        const error = document.createElement('div');
        error.className = 'error-message';
        error.textContent = message;
        input.parentElement.appendChild(error);
    }

    function validateEmail(email) {
        const re = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        return re.test(email);
    }
});
