// Function to display error messages
function showError(id, message) {
    document.getElementById(id).innerText = message;
}

// Function to clear error messages
function clearError(id) {
    document.getElementById(id).innerText = "";
}

// Validate Course Selection (CourseID)
function validate_course() {
    const course = document.getElementById("CourseID").value;
    if (course === "") {
        showError("course_err", "Please select a course.");
        return false;
    }
    clearError("course_err");
    return true;
}

// Validate Subject Name (SubjectName)
function validate_subject_name() {
    const subjectName = document.getElementById("SubjectName").value.trim();
    
    // Regular expression allows letters, numbers, spaces, and punctuation (dots, commas, etc.)
    const pattern = /^[A-Za-z0-9\s\.\-]{3,}$/;
    
    if (subjectName === "") {
        showError("subjectname_err", "Subject Name is required.");
        return false;
    } else if (!pattern.test(subjectName)) {
        showError("subjectname_err", "Subject Name must be at least 3 characters, and may include letters, numbers, spaces, and periods.");
        return false;
    }

    clearError("subjectname_err");
    return true;
}


// Combined validation function for the "Add Subject" form
function validate_add_subject_form() {
    const v1 = validate_course();
    const v2 = validate_subject_name();

    if (!(v1 && v2)) {
        document.getElementById("error").innerText = "Please fill all details correctly before submitting.";
        return false; // Prevent form submission
    }

    document.getElementById("error").innerText = "";
    return true; // Allow form submission
}


// Course field validation functions

function validate_course_name() {
    const name = document.getElementById("CourseName").value.trim();
    const pattern = /^[A-Za-z0-9\s\.\-]{3,}$/;
    if (name === "") {
        showError("coursename_err", "Course Name is required.");
        return false;
    } else if (!pattern.test(name)) {
        showError("coursename_err", "At least 3 characters. Letters, numbers, space, dot, and hyphen allowed.");
        return false;
    }
    clearError("coursename_err");
    return true;
}

function validate_course_code() {
    const code = document.getElementById("CourseCode").value.trim();
    const pattern = /^[A-Za-z0-9]{3,10}$/;
    if (code === "") {
        showError("coursecode_err", "Course Code is required.");
        return false;
    } else if (!pattern.test(code)) {
        showError("coursecode_err", "Alphanumeric, 3 to 10 characters only.");
        return false;
    }
    clearError("coursecode_err");
    return true;
}

function validate_department() {
    const dept = document.getElementById("Department").value;
    if (dept === "") {
        showError("department_err", "Please select a department.");
        return false;
    }
    clearError("department_err");
    return true;
}

function validate_course() {
    const v1 = validate_course_name();
    const v2 = validate_course_code();
    const v3 = validate_department();

    if (!(v1 && v2 && v3)) {
        showError("error", "Please fix the errors above before submitting.");
        return false;
    }

    clearError("error");
    return true;
}

// Validate Notification Title
function validate_title() {
    const title = document.getElementById("title").value.trim();
    const pattern = /^[A-Za-z0-9\s.,'-]{5,}$/;

    if (title === "") {
        showError("titleError", "Title is required.");
        return false;
    } else if (!pattern.test(title)) {
        showError("titleError", "At least 5 characters. Letters, numbers, spaces, comma, dot, hyphen allowed.");
        return false;
    }
    clearError("titleError");
    return true;
}

// Validate Notification Description
function validate_description() {
    const description = document.getElementById("description").value.trim();
    if (description === "") {
        showError("descriptionError", "Description is required.");
        return false;
    } else if (description.length < 10) {
        showError("descriptionError", "Description must be at least 10 characters.");
        return false;
    }
    clearError("descriptionError");
    return true;
}

// Validate Notice Date
function validate_notice_date() {
    const date = document.getElementById("notice_date").value;
    const today = new Date().toISOString().split('T')[0];

    if (date === "") {
        showError("dateError", "Please select a notice date.");
        return false;
    } else if (date < today) {
        showError("dateError", "Notice date cannot be in the past.");
        return false;
    }
    clearError("dateError");
    return true;
}


function validate_notice_form() {
    const v1 = validate_title();
    const v2 = validate_description();
    const v3 = validate_notice_date();

    if (!(v1 && v2 && v3)) {
        showError("error", "Please fix the errors above before submitting.");
        return false;
    }

    clearError("error");
    return true;
}



// Validate Course selection
function validate_fee_course() {
    const course = document.getElementById("CourseID").value;
    if (course === "") {
      showError("course_err", "Please select a course.");
      return false;
    }
    clearError("course_err");
    return true;
  }
  
  // Validate Fee Amount (must be numeric and positive)
  function validate_fee_amount() {
    const amount = document.getElementById("FeeAmount").value.trim();
    const pattern = /^[0-9]+(\.[0-9]{1,2})?$/;
  
    if (amount === "") {
      showError("fee_err", "Fee amount is required.");
      return false;
    } else if (!pattern.test(amount)) {
      showError("fee_err", "Enter a valid amount (e.g. 1000 or 1000.50).");
      return false;
    } else if (parseFloat(amount) < 1000) {
      showError("fee_err", "Fee amount must be at least 1000.");
      return false;
    }
  
    clearError("fee_err");
    return true;
  }
  
  
  // Validate Academic Year (format: 2024-25)
  function validate_academic_year() {
    const year = document.getElementById("AcademicYear").value.trim();
    const pattern = /^(20\d{2})-(\d{2})$/;
  
    if (year === "") {
      showError("year_err", "Academic year is required.");
      return false;
    } else if (!pattern.test(year)) {
      showError("year_err", "Format must be YYYY-YY (e.g. 2024-25).");
      return false;
    }
  
    clearError("year_err");
    return true;
  }
  
  // Combine all validations
  function validate_fee_form() {
    const v1 = validate_fee_course();
    const v2 = validate_fee_amount();
    const v3 = validate_academic_year();
  
    if (!(v1 && v2 && v3)) {
      showError("error", "Please correct the above errors before submitting.");
      return false;
    }
  
    clearError("error");
    return true;
  }


// Validation for result submission
function validateForm() {
    let valid = true;

    // Validate Student Selection
    let student = document.getElementById("StudentID").value;
    if (!student) {
        document.getElementById("student-error").innerText = "Please select a student.";
        valid = false;
    } else {
        document.getElementById("student-error").innerText = "";
    }

    // Validate Course Selection
    let course = document.getElementById("CourseID").value;
    if (!course) {
        document.getElementById("course-error").innerText = "Please select a course.";
        valid = false;
    } else {
        document.getElementById("course-error").innerText = "";
    }

    // Validate Subject Marks
    let subjects = document.querySelectorAll('[name^="marks_"]');
    let markValid = true;
    document.getElementById("marks-error").innerText = ""; // clear before loop

    subjects.forEach((subject) => {
        let marks = subject.value;
        if (marks === "" || isNaN(marks) || marks < 0 || marks > 100) {
            markValid = false;
        }
    });

    if (!markValid) {
        document.getElementById("marks-error").innerText = "Please enter valid marks (0-100) for all subjects.";
        valid = false;
    }

    return valid;
}

