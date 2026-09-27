document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("registerForm");
    const submitBtn = document.getElementById("submitBtn");

    // Regular expressions 
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const phoneRegex = /^[0-9+\-\s]{7,15}$/;

    // Helper function to clear previous error messages
    function clearErrors() {
        const errorElements = document.querySelectorAll(".error-msg");
        errorElements.forEach((el) => {
            el.textContent = "";
        });
    }

    // Helper function to set an inline error message
    function setError(id, message) {
        const errorElement = document.getElementById(id);
        if (errorElement) {
            errorElement.textContent = message;
        }
    }

    form.addEventListener("submit", (e) => {
        // Clear existing errors on each submission attempt
        clearErrors();

        let isValid = true;

        // Retrieve field values
        const name = document.getElementById("customer_name").value.trim();
        const email = document.getElementById("customer_email").value.trim();
        const pass = document.getElementById("customer_pass").value;
        const country = document.getElementById("customer_country").value.trim();
        const city = document.getElementById("customer_city").value.trim();
        const contact = document.getElementById("customer_contact").value.trim();

        // 1. Full Name Validation
        if (name === "") {
            setError("name-error", "Full name is required.");
            isValid = false;
        } else if (name.length > 100) {
            setError("name-error", "Full name cannot exceed 100 characters.");
            isValid = false;
        }

        // 2. Email Validation
        if (email === "") {
            setError("email-error", "Email is required.");
            isValid = false;
        } else if (!emailRegex.test(email)) {
            setError("email-error", "Please enter a valid email address.");
            isValid = false;
        } else if (email.length > 100) {
            setError("email-error", "Email cannot exceed 100 characters.");
            isValid = false;
        }

        // 3. Password Validation
        if (pass === "") {
            setError("pass-error", "Password is required.");
            isValid = false;
        } else if (pass.length < 6) {
            setError("pass-error", "Password must be at least 6 characters.");
            isValid = false;
        }

        // 4. Country Validation
        if (country === "") {
            setError("country-error", "Please select a country.");
            isValid = false;
        }

        // 5. City Validation
        if (city === "") {
            setError("city-error", "City is required.");
            isValid = false;
        } else if (city.length > 50) {
            setError("city-error", "City cannot exceed 50 characters.");
            isValid = false;
        }

        // 6. Contact Number Validation
        if (contact === "") {
            setError("contact-error", "Contact number is required.");
            isValid = false;
        } else if (!phoneRegex.test(contact)) {
            setError("contact-error", "Invalid contact number format (must be 7–15 digits, +, or -).");
            isValid = false;
        }

        // Prevent submission if any check fails
        if (!isValid) {
            e.preventDefault();
            return;
        }

        // Optional UX improvement: Show loading state on submit button
        submitBtn.disabled = true;
        submitBtn.textContent = "Registering...";
    });
});