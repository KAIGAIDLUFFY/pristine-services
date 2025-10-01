// scripts.js

document.addEventListener('DOMContentLoaded', function () {
    // Contact form submission
    const contactForm = document.getElementById('contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent the default form submission

            // Get form values
            const Name = document.getElementById('Name').value;
            const Email = document.getElementById('Email').value;
            const Message = document.getElementById('Message').value;

            // Simple form validation
            if (Name === '' || Email === '' || Message === '') {
                alert('Please fill in all fields.');
                return;
            }

            // Display a success message (for example purposes)
            alert(`Thank you, ${Name}. Your message has been sent.`);

            // Reset the form
            contactForm.reset();
        });
    }

    // Booking form submission
    const bookingForm = document.getElementById('booking-form');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent the default form submission

            // Get form values
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;
            const service = document.getElementById('service').value;
            const date = document.getElementById('date').value;

            // Simple form validation
            if (name === '' || email === '' || phone === '' || service === '' || date === '') {
                alert('Please fill in all fields.');
                return;
            }

            // Display a success message (for example purposes)
            alert(`Thank you, ${name}. Your booking for ${service} on ${date} has been received.`);

            // Reset the form
            bookingForm.reset();
        });
    }
});
