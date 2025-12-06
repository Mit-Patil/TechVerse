function showError(id, message) {
    document.getElementById(id).innerText = message;
}

function clearError(id) {
    document.getElementById(id).innerText = "";
}

// Student field validation functions

function validate_firstname() {
    const fname = document.getElementById("FirstName").value.trim();
    const pattern = /^[A-Za-z]{2,}$/;
    if (fname === "") {
        showError("firstname_err", "First Name is required");
        return false;
    } else if (!pattern.test(fname)) {
        showError("firstname_err", "Only letters, at least 2 characters");
        return false;
    }
    clearError("firstname_err");
    return true;
}

function validate_lastname() {
    const lname = document.getElementById("LastName").value.trim();
    const pattern = /^[A-Za-z]{2,}$/;
    if (lname === "") {
        showError("lastname_err", "Last Name is required");
        return false;
    } else if (!pattern.test(lname)) {
        showError("lastname_err", "Only letters, at least 2 characters");
        return false;
    }
    clearError("lastname_err");
    return true;
}

function validate_dob() {
    const dob = document.getElementById("DateOfBirth").value;
    if (dob === "") {
        showError("dob_err", "Date of Birth is required");
        return false;
    }
    clearError("dob_err");
    return true;
}

function validate_gender() {
    const gender = document.getElementById("Gender").value;
    if (gender === "") {
        showError("gender_err", "Please select your gender");
        return false;
    }
    clearError("gender_err");
    return true;
}

function validate_contact() {
    const contact = document.getElementById("ContactNumber").value.trim();
    const pattern = /^[6-9]\d{9}$/;
    if (contact === "") {
        showError("contact_err", "Contact number is required");
        return false;
    } else if (!pattern.test(contact)) {
        showError("contact_err", "Enter valid 10-digit Indian mobile number, start from 6-9");
        return false;
    }
    clearError("contact_err");
    return true;
}

function validate_email() {
    const email = document.getElementById("Email").value.trim();
    const pattern = /^[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/;
    if (email === "") {
        showError("email_err", "Email is required");
        return false;
    } else if (!pattern.test(email)) {
        showError("email_err", "Enter a valid email address");
        return false;
    }
    clearError("email_err");
    return true;
}

function validate_address() {
    const address = document.getElementById("Address").value.trim();
    if (address === "") {
        showError("address_err", "Address is required");
        return false;
    }
    clearError("address_err");
    return true;
}

function validate_profile() {
    const profileInput = document.getElementById("Profile");
    const profileValue = profileInput.value;

    // Check if current profile image is already displayed
    const currentProfileImage = document.querySelector('.container img[src]');

    // Skip validation if existing image is shown and no new file is selected
    if (currentProfileImage && currentProfileImage.src.trim() !== "" && profileValue === "") {
        clearError("profile_err");
        return true;
    }

    // Validate uploaded image if new one is selected
    const allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif|\.webp)$/i;

    if (profileValue === "") {
        showError("profile_err", "Please upload your profile image");
        return false;
    } else if (!allowedExtensions.exec(profileValue)) {
        showError("profile_err", "Allowed formats: jpg, jpeg, png, gif, webp");
        return false;
    }

    clearError("profile_err");
    return true;
}


// Student field validation combined

function validate_student_feild() {
    const v1 = validate_firstname();
    const v2 = validate_lastname();
    const v3 = validate_dob();
    const v4 = validate_gender();
    const v5 = validate_contact();
    const v6 = validate_email();
    const v7 = validate_address();
    const v8 = validate_profile();

    if (!(v1 && v2 && v3 && v4 && v5 && v6 && v7 && v8)) {
        document.getElementById("error").innerText = "Please fill all details First before submitting.";
        return false;
    }

    document.getElementById("error").innerText = "";
    return true;
}


// Academic field validation functions

function validate_department() {
    const department = document.getElementById("Department").value;
    if (department === "") {
        showError("department_err", "Department is required");
        return false;
    }
    clearError("department_err");
    return true;
}

function validate_course() {
    const course = document.getElementById("CourseID").value;
    if (course === "") {
        showError("course_err", "Course is required");
        return false;
    }
    clearError("course_err");
    return true;
}

// Academic field validation combined

function validate_student_academic() {
    const v1 = validate_department();
    const v2 = validate_course();

    if (!(v1 && v2)) {
        document.getElementById("error").innerText = "Please fill all details First before submitting.";
        return false;
    }

    document.getElementById("error").innerText = "";
    return true;
}


// Account creation and update details validations

function validate_username() {
    const username = document.getElementById("Username").value;
    if (username.trim() === "") {
        showError("username_err", "Username is required");
        return false;
    }
    clearError("username_err");
    return true;
}

function validate_password() {
    const password = document.getElementById("Password").value;
    if (password.trim() === "") {
        showError("password_err", "Password is required");
        return false;
    } else if (password.length < 6) {
        showError("password_err", "Password must be at least 6 characters");
        return false;
    }
    clearError("password_err");
    return true;
}



// Account and Personal Details validation combined

function validate_student_account() {
    const v1 = validate_firstname();
    const v2 = validate_lastname();
    const v3 = validate_dob();
    const v4 = validate_gender();
    const v5 = validate_contact();
    const v6 = validate_email();
    const v7 = validate_address();
    const v8 = validate_profile();
    const v9 = validate_username();
    const v10 = validate_password();

    // Check if all validations passed
    const allValid = v1 && v2 && v3 && v4 && v5 && v6 && v7 && v8 && v9 && v10;

    if (!allValid) {
        document.getElementById("error").innerText = "Please fill all details before submitting.";
        return false; // Prevent form submission
    }

    document.getElementById("error").innerText = "";
    return true; // Allow form submission
}

//faculty department
function validate_department_text() {
    const department = document.getElementById("Department").value.trim();
    const pattern = /^[A-Za-z0-9\s\.\-]{2,}$/;
    if (department === "") {
        showError("department_err", "Department is required");
        return false;
    } else if (!pattern.test(department)) {
        showError("department_err", "Only letters, at least 2 characters");
        return false;
    }
    clearError("department_err");
    return true;
}

function validate_faculty_feild() {//faculty validations
    const v1 = validate_firstname();
    const v2 = validate_lastname();
    const v3 = validate_dob();
    const v4 = validate_gender();
    const v5 = validate_contact();
    const v6 = validate_email();
    const v7 = validate_address();
    const v8 = validate_department_text();
    const v9 = validate_profile();

    if (!(v1 && v2 && v3 && v4 && v5 && v6 && v7 && v8 && v9)) {
        document.getElementById("error").innerText = "Please fill all details First before submitting.";
        return false;
    }

    document.getElementById("error").innerText = "";
    return true;
}


function validate_passing() {
    const passing = document.getElementById("passing").value;
    if (passing === "") {
        showError("passing_err", "This Information is required");
        return false;
    }
    clearError("passing_err");
    return true;
}

function validate_education() {
    const education = document.getElementById("Education").value;
    if (education === "") {
        showError("education_err", "This Information is required");
        return false;
    }
    clearError("education_err");
    return true;
}

function validate_qualifications() {
    const Qualifications = document.getElementById("Qualifications").value.trim();
    const pattern = /^[A-Za-z0-9\s\.\-]{2,}$/;

    if (Qualifications === "") {
        showError("qualification_err", "Qualifications is required");
        return false;
    } else if (!pattern.test(Qualifications)) {
        showError("qualification_err", "Invalid qualifications format. Use letters, numbers, spaces, and standard punctuation.");
        return false;
    }
    clearError("qualification_err");
    return true;
}


// Academic field validation combined for faculty

function validate_faculty_academic() {

    const v1 = validate_passing();
    const v2 = validate_education();
    const v3 = validate_qualifications();


    if (!(v1 && v2 && v3)) {
        document.getElementById("error").innerText = "Please fill all details First before submitting.";
        return false;
    }

    document.getElementById("error").innerText = "";
    return true;
}

// Account and Personal Details validation combined for faculty

function validate_faculty_account() {
    const v1 = validate_firstname();
    const v2 = validate_lastname();
    const v3 = validate_dob();
    const v4 = validate_gender();
    const v5 = validate_contact();
    const v6 = validate_email();
    const v7 = validate_address();
    const v8 = validate_department_text();
    const v9 = validate_profile();
    const v10 = validate_username();
    const v11 = validate_password();

    // Check if all validations passed
    const allValid = v1 && v2 && v3 && v4 && v5 && v6 && v7 && v8 && v9 && v10 && v11;

    if (!allValid) {
        document.getElementById("error").innerText = "Please fill all details before submitting.";
        return false; // Prevent form submission
    }

    document.getElementById("error").innerText = "";
    return true; // Allow form submission
}

//profile function for all users :-- 

//Admin--
function validate_admin_profile_form() {
    const v1 = validate_firstname();
    const v2 = validate_lastname();
    const v3 = validate_gender();
    const v4 = validate_contact();
    const v5 = validate_email();

    const v6 = validate_username();
    const v7 = validate_password();

    // Check if all validations passed
    const allValid = v1 && v2 && v3 && v4 && v5 && v6 && v7;

    if (!allValid) {
        document.getElementById("error").innerText = "Please fill all details before submitting.";
        return false; // Prevent form submission
    }

    document.getElementById("error").innerText = "";
    return true; // Allow form submission
}

//student
function validate_student_profile_form() {
    const v1 = validate_firstname();
    const v2 = validate_lastname();
    const v3 = validate_dob();
    const v4 = validate_gender();
    const v5 = validate_contact();
    const v6 = validate_email();
    const v7 = validate_address();
    const v8 = validate_profile();

    const v9 = validate_department();
    const v10 = validate_course();

    const v11 = validate_username();
    const v12 = validate_password();

    // Check if all validations passed
    const allValid = v1 && v2 && v3 && v4 && v5 && v6 && v7 && v8 && v9 && v10 && v11 && v12;

    if (!allValid) {
        document.getElementById("error").innerText = "Please fill all details before submitting.";
        return false; // Prevent form submission
    }

    document.getElementById("error").innerText = "";
    return true; // Allow form submission
}

//faculty 
function validate_faculty_profile_form() {
    const v1 = validate_firstname();
    const v2 = validate_lastname();
    const v3 = validate_dob();
    const v4 = validate_gender();
    const v5 = validate_contact();
    const v6 = validate_email();
    const v7 = validate_address();
    const v8 = validate_department_text();
    const v9 = validate_profile();

    const v10 = validate_passing();
    const v11 = validate_education();
    const v12 = validate_qualifications();

    const v13 = validate_username();
    const v14 = validate_password();

    // Check if all validations passed
    const allValid = v1 && v2 && v3 && v4 && v5 && v6 && v7 && v8 && v9 && v10 && v11 && v12 && v13 && v14;

    if (!allValid) {
        document.getElementById("error").innerText = "Please fill all details before submitting.";
        return false; // Prevent form submission
    }

    document.getElementById("error").innerText = "";
    return true; // Allow form submission
}


//forget password validation ---

function validate_forgot_password_form()
{
    const v1 = validate_email();

    // Check if all validations passed
    const allValid = v1;

    if (!allValid) {
        document.getElementById("error").innerText = "Please fill all details before submitting.";
        return false; // Prevent form submission
    }

    document.getElementById("error").innerText = "";
    return true; // Allow form submission
}

function validate_con_password() {
    const password = document.getElementById("Password").value;
    const con_password = document.getElementById("con_Password").value;
    if (con_password.trim() === "") {
        showError("con_password_err", "Password is required");
        return false;
    } else if (con_password.length < 6) {
        showError("con_password_err", "Password must be at least 6 characters");
        return false;
    }
    else if (password != con_password) {
        showError("con_password_err", "both Password must be same");
        return false;
    }
    clearError("con_password_err");
    return true;
}

function validate_reset_password_form()
{
    const v1 = validate_password();
    const v2 = validate_con_password();


    // Check if all validations passed
    const allValid = v1 && v2;

    if (!allValid) {
        document.getElementById("error").innerText = "Please fill all details before submitting.";
        return false; // Prevent form submission
    }

    document.getElementById("error").innerText = "";
    return true; // Allow form submission
}