/**
 * Displays an error message inside the given element.
 * @param {string} message The error message to display.
 */
function showError(message) {
    const errorElement = document.getElementById("error-message");
    errorElement.textContent = message;
    errorElement.style.color = 'red';
}

/**
 * Validates a username (3-20 characters, only letters, numbers, and underscores).
 * @param {string} username
 * @returns {boolean|string} - Returns true if valid, otherwise an error message.
 */
function isValidUsername(username) {
    return /^[a-zA-Z0-9_]{3,20}$/.test(username) || "Username must be 3-20 characters long and contain only letters, numbers, and underscores.";
}

/**
 * Validates an email format.
 * @param {string} email
 * @returns {boolean|string} - Returns true if valid, otherwise an error message.
 */
function isValidEmail(email) {
    return /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email) || "Invalid email format.";
}

/**
 * Validates a password (at least 6 characters).
 * @param {string} password
 * @returns {boolean|string} - Returns true if valid, otherwise an error message.
 */
function isValidPassword(password) {
    if (!password.trim()) {
        return "Password cannot be empty.";
    }

    const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/;
    if (!passwordRegex.test(password)) {
        return "Password must be at least 6 characters long and contain at least one letter and one number.";
    }

    return true;
}

/**
 * Validates an uploaded image file (JPG, JPEG, PNG, max size 500KB).
 * @param {File} file
 * @returns {boolean|string} Returns true if valid, else an error message.
 */
function isValidFile(file) {
    if (!file) return true;
    const allowedTypes = ["image/jpeg", "image/jpg", "image/png"];
    if (!allowedTypes.includes(file.type)) return "Only JPG, JPEG, PNG files are allowed.";
    if (file.size > 500000) return "File size must be less than 500KB.";
    return true;
}

/**
 * Handles the signup form validation.
 * @param {Event} event
 */
function validateSignupForm(event) {
    event.preventDefault();
    const username = document.getElementById('username').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    const profilePic = document.getElementById('profilePic').files[0];

    const validators = [
        [username, isValidUsername],
        [email, isValidEmail],
        [password, isValidPassword],
        [profilePic, isValidFile]
    ];

    for (const [value, validator] of validators) {
        const result = validator(value);
        if (result !== true) {
            showError(result);
            return false;
        }
    }

    document.getElementById("signup-form").submit();
}


/** 
 * Handle Login form validation
 * $param {Event} event
 */

function validateLoginForm(event) {
    event.preventDefault();
    var username = document.getElementById('username').value.trim();
    var password = document.getElementById('password').value.trim();
    var errorElement = document.getElementById("error-message");
    errorElement.innerHTML = "";
    const validators = [
        [username, isValidUsername],
        [password, isValidPassword]
    ];

    for (const [value, validator] of validators) {
        const result = validator(value);
        if (result !== true) {
            showError(result);
            return false;
        }
    }
    document.getElementById("login-form").submit();
}