function registerCustomer() {
    var name = document.getElementById("customer_name").value.trim();
    var email = document.getElementById("customer_email").value.trim();
    var pass = document.getElementById("customer_pass").value;
    var country = document.getElementById("customer_country").value.trim();
    var city = document.getElementById("customer_city").value.trim();
    var contact = document.getElementById("customer_contact").value.trim();

    var messageEl = document.getElementById("formMessage");
    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    // Standard password policy:
    // - At least 8 characters
    // - At least 1 lowercase letter (?=.*[a-z])
    // - At least 1 uppercase letter (?=.*[A-Z])
    // - At least 1 number (?=.*\d)
    // - At least 1 special character (?=.*[@$!%*?&#^()_\-+=[\]{}|;:,.<>])
    var passPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#^()_\-+=[\]{}|;:,.<>])[A-Za-z\d@$!%*?&#^()_\-+=[\]{}|;:,.<>]{8,}$/;

    // Reset message style
    messageEl.style.color = "inherit";

    if (!name || !email || !pass.trim() || !country || !city || !contact) {
        messageEl.style.color = "red";
        messageEl.textContent = "Please fill in all required fields.";
        return;
    }

    if (!emailPattern.test(email)) {
        messageEl.style.color = "red";
        messageEl.textContent = "Please enter a valid email address.";
        return;
    }

    if (!passPattern.test(pass)) {
        messageEl.style.color = "red";
        messageEl.textContent = "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one digit, and one special character.";
        return;
    }

    var formData = new FormData(document.getElementById("registerForm"));

    fetch("../actions/customer_register_action.php", {
        method: "POST",
        body: formData
    })
    .then(async function (response) {
        // Read raw text first so a PHP crash doesn't hide behind a JSON parse error
        var text = await response.text();
        try {
            return JSON.parse(text);
        } catch (err) {
            // PHP emitted an error message/HTML page instead of valid JSON
            throw new Error(text);
        }
    })
    .then(function (data) {
        messageEl.style.color = data.success ? "green" : "red";
        messageEl.textContent = data.message;

        if (data.success) {
            document.getElementById("registerForm").reset();
        }
    })
    .catch(function (error) {
        console.error("Server Error:", error);
        // Print the actual error output onto the screen
        messageEl.innerHTML = "<div style='color:red; text-align:left; background:#fee; padding:10px; border:1px solid red;'><pre>" + error.message + "</pre></div>";
    });
}
// Real-time Password Strength Meter
document.addEventListener("DOMContentLoaded", function () {
    var passInput = document.getElementById("customer_pass");
    var strengthBar = document.getElementById("strengthBar");
    var strengthText = document.getElementById("strengthText");

    if (!passInput) return;

    passInput.addEventListener("input", function () {
        var val = passInput.value;
        var score = 0;

        if (val.length === 0) {
            strengthBar.style.width = "0%";
            strengthText.textContent = "";
            return;
        }

        // Checklist tests
        var hasLength = val.length >= 8;
        var hasLower = /[a-z]/.test(val);
        var hasUpper = /[A-Z]/.test(val);
        var hasNumber = /\d/.test(val);
        var hasSpecial = /[@$!%*?&#^()_\-+=[\]{}|;:,.<>]/.test(val);

        if (hasLength) score++;
        if (hasLower) score++;
        if (hasUpper) score++;
        if (hasNumber) score++;
        if (hasSpecial) score++;

        // Render based on score
        if (score <= 2) {
            strengthBar.style.width = "30%";
            strengthBar.style.backgroundColor = "#dc3545"; // Red
            strengthText.style.color = "#dc3545";
            strengthText.textContent = "Weak (missing required character types)";
        } else if (score < 5) {
            strengthBar.style.width = "65%";
            strengthBar.style.backgroundColor = "#fd7e14"; // Orange
            strengthText.style.color = "#fd7e14";
            strengthText.textContent = "Moderate (almost there)";
        } else {
            strengthBar.style.width = "100%";
            strengthBar.style.backgroundColor = "#28a745"; // Green
            strengthText.style.color = "#28a745";
            strengthText.textContent = "Strong password!";
        }
    });
});