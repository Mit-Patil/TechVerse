

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Document</title>
            <link rel="stylesheet" href="../css/display.css">
              <!-- Form CSS -->
              <link rel="stylesheet" href="../css/form.css">  
              <!-- Themify Icons for Header -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/themify-icons@1.0.1/css/themify-icons.css" />
  
  <!-- JQuery for Loading Header/Footer -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <style>
#fakePaymentModal {
  display: none;
  position: fixed;
  top: 20px;
  right: 20px;
  width: 520px;
  max-height: 90vh; /* limit the height to viewport height */
  overflow-y: auto;  /* enables vertical scrolling */
  background: #f9f9f9;
  border: 1px solid #ddd;
  padding: 25px;
  border-radius: 10px;
  z-index: 1000;
  box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.3);
}
#fakePaymentModal::-webkit-scrollbar {
  width: 8px;
}
#fakePaymentModal::-webkit-scrollbar-thumb {
  background-color: #ccc;
  border-radius: 10px;
}

.modal {
  display: none;
  position: fixed;
  top: 30%;
  left: 50%;
  transform: translate(-50%, -30%);
  width: 400px;
  background: #fff;
  padding: 25px;
  border-radius: 10px;
  box-shadow: 0px 0px 20px rgba(0,0,0,0.3);
  z-index: 2000;
}
.modal button {
  margin: 5px;
}

</style>
        </head>
        
        <?php

session_start();
include_once "../user/connection.php";
if (!isset($_SESSION['email'])) {
    header("Location: ../user/login.php");
    exit;
}

$email=$_SESSION['email'];

$FirstName="";
$LastName="";
$Email="";

$flag=1;
$student_detail="SELECT StudentID,FirstName,LastName,Email FROM Students WHERE Email='$email'";

$result_student=$conn->query($student_detail);

if($result_student->num_rows==1)
{
    while($row=$result_student->fetch_assoc())
    {
        $StudentID=$row['StudentID'];
        $FirstName=$row['FirstName'];
        $LastName=$row['LastName'];
        $Email=$row['Email'];
    }
}
else{
    echo "Error To Fetch Student Details";
    $flag=0;
    
}

$CourseID="";
$course_details="SELECT CourseID FROM Enrollments WHERE StudentID=$StudentID";
$result_course=$conn->query($course_details);

if($result_course->num_rows==1)
{
    while($row=$result_course->fetch_assoc())
    {
        $CourseID=$row['CourseID'];
    }
}
else{
    echo "Error To Fetch Course Details";
    $flag=0;

}


$get_fess_details="SELECT FeeAmount FROM FeeStructure WHERE CourseID=$CourseID";
$result_fess=$conn->query($get_fess_details);
$FeeAmount="";


if($result_fess->num_rows>0)
{
    while($row=$result_fess->fetch_assoc())
    {
        $FeeAmount=$row['FeeAmount'];
    }
}
else{

    echo "Error To Fetch Fees Details";
    $flag=0;
}




$get_status="SELECT PaymentStatus FROM StudentFees WHERE StudentID=$StudentID";
$result_studentfess=$conn->query($get_status);

$PaymentStatus="";
if($result_studentfess->num_rows==1)
{
    while($row=$result_studentfess->fetch_assoc())
    {
        $PaymentStatus=$row['PaymentStatus'];
    }
}
else if($result_studentfess->num_rows<1){
    $PaymentStatus="Pending";
    $StudentFees="INSERT INTO StudentFees(StudentID,CourseID,FeeAmount,PaymentStatus) VALUES('$StudentID','$CourseID','$FeeAmount','$PaymentStatus')";

    if($conn->query($StudentFees)==TRUE)
    {
        echo "Inserted";
    }
    else{
            echo "Error To Fetch Or Insert StudentFees Details";
            $flag=0;
    }
}
else{

    echo "Error To Fetch Or Insert StudentFees Details";
    $flag=0;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pay_fees'])) 
    {
    $update_payment="UPDATE StudentFees SET PaymentStatus='Paid' WHERE StudentID=$StudentID";

    if($conn->query($update_payment)==TRUE)
    {
    $PaymentStatus="Paid";
    // echo "
    //     <h4>Fees Are Paid</h4><br/><br/>
    //     ";
    }
    else{
        echo "
        <h4>Error to Update Payment Status</h4><br/><br/>
        ";
    }
}

if($flag==0)
{
    echo "Error To Fetch All Details";
}
else{
    echo "
    <h4>$FirstName $LastName</h4>
    <h4>$Email</h4><br/>
    ";

    echo "
    <table>
    <th colspan=2>Payment Details</th>
    ";

    echo "
    <tr>
    <td>Fee Amount</td>
    <td>$FeeAmount</td>
    </tr>
    <tr>
    <td>PaymentStatus</td>
    <td>$PaymentStatus</td>
    </tr>
    ";

    echo "</table><br/><br/>";

    if($PaymentStatus=="Pending")
    {
        echo "
        <form method='POST'>
            <button type='button' onclick='openFakePayment()'>Proceed to Pay</button>
        </form>
        ";
    }
    else{
        echo "
        <h4>Fees Are  Paid</h4><br/><br/>
        ";
    }

}


$conn->close();


?>
        <body>
            <!-- Header -->
<div class="header-container"></div>

<!-- Main Form Area -->
<script>
  $(document).ready(function () {
    $(".header-container").load("../header.php");
    $(".footer-container").load("../footer.php");
  });
</script>

<!-- Payment Option Modal -->
<div id="paymentOptionModal" style="display: none;">
  <h3>Select Payment Method</h3>
  <button onclick="choosePaymentMethod('upi')">UPI</button>
  <button onclick="choosePaymentMethod('card')">Debit/Credit Card</button>
  <button onclick="closePaymentOption()">Cancel</button>
</div>

<!-- Fake Payment -->
<div id="fakePaymentModal">
  <h3>Payment Gateway</h3>
  <form method="POST" onsubmit="return validatePaymentForm(this)">
    <!-- UPI Fields -->
    <div id="upiFields" style="display: none;">
      <label>UPI ID:</label><br />
      <input type="text" name="upi_id" placeholder="yourname@bank" oninput="validateUpi(this)">
      <span class="error" id="upiError"></span><br /><br />
    </div>

    <!-- Card Fields -->
    <div id="cardFields" style="display: none;">
      <label>Card Number:</label><br />
      <input type="text" name="card" placeholder="1234567890123456" maxlength="16" oninput="validateCard(this)">
      <span class="error" id="cardError"></span><br /><br />

      <label>Expiry (MM/YY):</label><br />
      <input type="text" name="expiry" placeholder="MM/YY" maxlength="5" oninput="validateExpiry(this)">
      <span class="error" id="expiryError"></span><br /><br />

      <label>CVV:</label><br />
      <input type="text" name="cvv" placeholder="123" maxlength="3" oninput="validateCvv(this)">
      <span class="error" id="cvvError"></span><br /><br />
    </div>

    <input type="hidden" name="pay_fees" value="1" />

    <button type="submit">Pay ₹<?php echo htmlspecialchars($FeeAmount ?? '0'); ?></button>
    <button type="button" onclick="closeFakePayment()">Cancel</button>
  </form>
</div>


<script>
function openFakePayment() {
  document.getElementById("paymentOptionModal").style.display = "block";
}

function closePaymentOption() {
  document.getElementById("paymentOptionModal").style.display = "none";
}

function choosePaymentMethod(method) {
  document.getElementById("paymentOptionModal").style.display = "none";
  document.getElementById("fakePaymentModal").style.display = "block";

  // Reset error messages
  clearAllErrors();

  if (method === "upi") {
    document.getElementById("upiFields").style.display = "block";
    document.getElementById("cardFields").style.display = "none";
  } else if (method === "card") {
    document.getElementById("upiFields").style.display = "none";
    document.getElementById("cardFields").style.display = "block";
  }
}

function closeFakePayment() {
  document.getElementById("fakePaymentModal").style.display = "none";
  clearAllErrors();
}

function clearAllErrors() {
  document.getElementById("upiError").textContent = "";
  document.getElementById("cardError").textContent = "";
  document.getElementById("expiryError").textContent = "";
  document.getElementById("cvvError").textContent = "";
}

// Realtime validation
function validateUpi(input) {
  const error = document.getElementById("upiError");
  const regex = /^[\w.\-]{2,256}@[a-zA-Z]{2,64}$/;
  error.textContent = regex.test(input.value.trim()) ? "" : "Invalid UPI ID (e.g., yourname@bank)";
}

function validateCard(input) {
  const error = document.getElementById("cardError");
  const regex = /^\d{16}$/;
  error.textContent = regex.test(input.value.trim()) ? "" : "Card number must be 16 digits.";
}

function validateExpiry(input) {
  const error = document.getElementById("expiryError");
  const expiry = input.value.trim();
  const regex = /^(0[1-9]|1[0-2])\/\d{2}$/;

  if (!regex.test(expiry)) {
    error.textContent = "Expiry must be in MM/YY format.";
    return false;
  }

  const [expMonth, expYear] = expiry.split("/").map(str => parseInt(str, 10));

  // Get the current date and extract current month and year
  const now = new Date();
  const currentMonth = now.getMonth() + 1;  // JavaScript months are 0-indexed, so we add 1
  const currentYear = now.getFullYear();   // Get the current year (e.g., 2025)

  const fullExpYear = 2000 + expYear;  // Convert 2-digit year to 4-digit (e.g., 25 -> 2025)

  console.log(`Current Date: ${currentMonth}/${currentYear}`);
  console.log(`Expiry Date: ${expMonth}/${fullExpYear}`);

  // Validate if the expiry year is less than the current year
  if (fullExpYear < currentYear) {
    error.textContent = "Card is expired.";
    return false;
  } 
  // Validate if expiry year is the same as current year but expiry month is before current month
  else if (fullExpYear === currentYear && expMonth < currentMonth) {
    error.textContent = "Card is expired.";
    return false;
  } else {
    error.textContent = ""; // Clear error if valid
  }

  return true;
}

function validateCvv(input) {
  const error = document.getElementById("cvvError");
  const regex = /^\d{3}$/;
  error.textContent = regex.test(input.value.trim()) ? "" : "CVV must be 3 digits.";
}

// Final form validation before submit
function validatePaymentForm(form) {
  let valid = true;
  const upiVisible = document.getElementById("upiFields").style.display === "block";
  const cardVisible = document.getElementById("cardFields").style.display === "block";

  clearAllErrors();

  if (upiVisible) {
    const upiValue = form.upi_id.value.trim();
    if (!/^[\w.\-]{2,256}@[a-zA-Z]{2,64}$/.test(upiValue)) {
      document.getElementById("upiError").textContent = "Invalid UPI ID (e.g., yourname@bank)";
      valid = false;
    }
  }

  if (cardVisible) {
    const card = form.card.value.trim();
    const expiry = form.expiry.value.trim();
    const cvv = form.cvv.value.trim();

    if (!/^\d{16}$/.test(card)) {
      document.getElementById("cardError").textContent = "Card number must be 16 digits.";
      valid = false;
    }

    // Live validation for expiry date
    if (!validateExpiry(form.expiry)) {
      valid = false;
    }

    if (!/^\d{3}$/.test(cvv)) {
      document.getElementById("cvvError").textContent = "CVV must be 3 digits.";
      valid = false;
    }
  }

  return valid;
}

// Live validation for expiry date (on change)
document.getElementById("expiry").addEventListener("input", function() {
  validateExpiry(this);
});

</script>




        </body>
        </html>
