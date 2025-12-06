<?php


// $dbhost="localhost";
// $dbid="root";
// $dbpass="";
// $dbname="ims";

// $conn=new mysqli($dbhost,$dbid,$dbpass,$dbname);

// if(!$conn)
// {
//     echo "Error";
// }


$conn = mysqli_connect("localhost:3306","root","");

  if(!$conn)
  {
    die("Unable to connect to database");
  }

  $dbname = "ims";

  $sql = "CREATE DATABASE IF NOT EXISTS $dbname";

  if(!mysqli_query($conn,$sql))
  {
    die("Unable to create database");
  }

  $conn->close();



  $conn = mysqli_connect("localhost:3306","root","",$dbname);

  if(!$conn)
  {
    die("Unable to connect to database");
  }



  //Student table creation if not exists-------->
  $sql = "CREATE TABLE IF NOT EXISTS Students (
    StudentID INT PRIMARY KEY AUTO_INCREMENT,
    FirstName VARCHAR(50) NOT NULL,
    LastName VARCHAR(50) NOT NULL,
    DateOfBirth DATE NOT NULL,
    Gender ENUM('Male', 'Female', 'Other') NOT NULL,
    ContactNumber VARCHAR(15),
    Email VARCHAR(100) UNIQUE NOT NULL,
    Address TEXT,
    DateOfRegistration DATE DEFAULT CURRENT_DATE,
    Profile VARCHAR(200) NOT NULL
)";
  if(!mysqli_query($conn,$sql))
  {
    die("Unable to create Students table");
  }



  //AcademicDetails table creation if not exists-------->
  $sql = "CREATE TABLE IF NOT EXISTS AcademicDetails (
    AcademicID INT PRIMARY KEY AUTO_INCREMENT,
    StudentID INT NOT NULL,
    Department VARCHAR(50) NOT NULL,
    YearOfStudy INT NOT NULL,
    EnrollmentYear INT NOT NULL,
    FOREIGN KEY (StudentID) REFERENCES Students(StudentID)
        ON DELETE CASCADE
)";

  if(!mysqli_query($conn,$sql))
  {
    die("Unable to create Students table");
  }



    //Courses table creation if not exists-------->
  $sql = "CREATE TABLE IF NOT EXISTS Courses (
    CourseID INT PRIMARY KEY AUTO_INCREMENT,
    CourseName VARCHAR(100) NOT NULL,
    CourseCode VARCHAR(10) UNIQUE NOT NULL,
    Department VARCHAR(50) NOT NULL
)";
  if(!mysqli_query($conn,$sql))
  {
    die("Unable to create Courses table");
  }
    //Courses Manual Insertion---->
    $sql1="SELECT * FROM Courses";
    $result=$conn->query($sql1);
    if($result->num_rows>0)
    {
      //echo "Courses Already Assign";
    }
    else{
      $sql_i1="INSERT INTO Courses(CourseID,CourseName,CourseCode,Department) VALUES (1,'BSC IT',101,'IT')";
      $sql_i2="INSERT INTO Courses(CourseID,CourseName,CourseCode,Department) VALUES (2,'MSC IT',201,'IT')";
      $sql_i3="INSERT INTO Courses(CourseID,CourseName,CourseCode,Department) VALUES (3,'BSC ICT',102,'ICT')";
      $sql_i4="INSERT INTO Courses(CourseID,CourseName,CourseCode,Department) VALUES (4,'MSC ICT',202,'ICT')";
      $sql_i5="INSERT INTO Courses(CourseID,CourseName,CourseCode,Department) VALUES (5,'OTHER',301,'OTHER')";



      if($conn->query($sql_i1)==TRUE)
      {
        //echo "BSC IT COURSE Added";
      }
      else{
        echo "Error To ADD Course BSCIT";
      }

      if($conn->query($sql_i2)==TRUE)
      {
        //echo "MSC IT ADDED";
      }
      else{
        echo "Error To ADD MSC it";
      }

      if($conn->query($sql_i3)==TRUE)
      {
       // echo "BSC ICT ADDED";
      }
      else{
        echo "Error To ADD BSC ICT";
      }

      if($conn->query($sql_i4)==TRUE)
      {
       // echo "MSC ICT ADDED";
      }
      else{
        echo "Error To Add MSC ICT";
      }

      if($conn->query($sql_i5)==TRUE)
      {
       // echo "OTHER Added";
      }
      else{
        echo "Error To Add OTHER Course";
      }
    }



    //Enrollments table creation if not exists-------->
  $sql = "CREATE TABLE IF NOT EXISTS Enrollments (
    EnrollmentID INT PRIMARY KEY AUTO_INCREMENT,
    StudentID INT NOT NULL,
    CourseID INT NOT NULL,
    EnrollmentDate DATE DEFAULT CURRENT_DATE,
    FOREIGN KEY (StudentID) REFERENCES Students(StudentID)
        ON DELETE CASCADE,
    FOREIGN KEY (CourseID) REFERENCES Courses(CourseID)
        ON DELETE CASCADE
)";

  if(!mysqli_query($conn,$sql))
  {
    die("Unable to create Enrollments table");
  }



//Results table creation if not exists-------->///Modified----------------------------------------------------------------------------------------------
$sql = "CREATE TABLE IF NOT EXISTS Results (
  ResultID INT PRIMARY KEY AUTO_INCREMENT,
  StudentID INT NOT NULL,
  CourseID INT NOT NULL,
  TotalMarksObtained DECIMAL(5,2) NOT NULL,
  TotalSubjectMarks DECIMAL(5,2) NOT NULL,
  Percentage DECIMAL(5,2) NOT NULL,
  CGPA DECIMAL(3,2) NOT NULL,
  Grade CHAR(2) NOT NULL,
  CreatedDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UpdatedDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  Published BOOLEAN DEFAULT FALSE,
  FOREIGN KEY (StudentID) REFERENCES Students(StudentID) ON DELETE CASCADE,
  FOREIGN KEY (CourseID) REFERENCES Courses(CourseID) ON DELETE CASCADE
)";

if (!mysqli_query($conn, $sql)) {
  die("Unable to create Results table: " . mysqli_error($conn));
}

// Trigger for Auto Grade Calculation on Insert
$sql = "DROP TRIGGER IF EXISTS auto_calculate";
if (!mysqli_query($conn, $sql)) {
  die("Error dropping trigger: " . mysqli_error($conn));
}

$trigger_sql = "CREATE TRIGGER auto_calculate
BEFORE INSERT ON Results
FOR EACH ROW
BEGIN
  DECLARE grade CHAR(2);
  DECLARE percentage DECIMAL(5,2);
  DECLARE cgpa DECIMAL(3,2);
  
  -- Calculate Percentage
  SET percentage = (NEW.TotalMarksObtained / NEW.TotalSubjectMarks) * 100;
  SET NEW.Percentage = percentage;
  
  -- Calculate CGPA (assuming max CGPA is 10)
  SET cgpa = (percentage / 100) * 10;
  SET NEW.CGPA = ROUND(cgpa, 2);
  
  -- Set Grade based on Percentage
  IF percentage >= 90 THEN
      SET NEW.Grade = 'A+';
  ELSEIF percentage >= 80 THEN
      SET NEW.Grade = 'A';
  ELSEIF percentage >= 70 THEN
      SET NEW.Grade = 'B';
  ELSEIF percentage >= 60 THEN
      SET NEW.Grade = 'C';
  ELSEIF percentage >= 50 THEN
      SET NEW.Grade = 'D';
  ELSE
      SET NEW.Grade = 'F';
  END IF;
END";

if (!mysqli_query($conn, $trigger_sql)) {
  die("Error creating trigger: " . mysqli_error($conn));
}

// Trigger for Auto Grade Calculation on Update
$sql = "DROP TRIGGER IF EXISTS auto_calculate_update";
if (!mysqli_query($conn, $sql)) {
  die("Error dropping trigger: " . mysqli_error($conn));
}

$trigger_sql = "CREATE TRIGGER auto_calculate_update
BEFORE UPDATE ON Results
FOR EACH ROW
BEGIN
  DECLARE grade CHAR(2);
  DECLARE percentage DECIMAL(5,2);
  DECLARE cgpa DECIMAL(3,2);
  
  -- Calculate Percentage
  SET percentage = (NEW.TotalMarksObtained / NEW.TotalSubjectMarks) * 100;
  SET NEW.Percentage = percentage;
  
  -- Calculate CGPA (assuming max CGPA is 10)
  SET cgpa = (percentage / 100) * 10;
  SET NEW.CGPA = ROUND(cgpa, 2);
  
  -- Set Grade based on Percentage
  IF percentage >= 90 THEN
      SET NEW.Grade = 'A+';
  ELSEIF percentage >= 80 THEN
      SET NEW.Grade = 'A';
  ELSEIF percentage >= 70 THEN
      SET NEW.Grade = 'B';
  ELSEIF percentage >= 60 THEN
      SET NEW.Grade = 'C';
  ELSEIF percentage >= 50 THEN
      SET NEW.Grade = 'D';
  ELSE
      SET NEW.Grade = 'F';
  END IF;
END";

if (!mysqli_query($conn, $trigger_sql)) {
  die("Error creating trigger: " . mysqli_error($conn));
}


  //Faculty table creation if not exists-------->
  $sql = "CREATE TABLE IF NOT EXISTS Faculty (
    FacultyID INT PRIMARY KEY AUTO_INCREMENT,
    FirstName VARCHAR(50) NOT NULL,
    LastName VARCHAR(50) NOT NULL,
    DateOfBirth DATE NOT NULL,
    Gender ENUM('Male', 'Female', 'Other') NOT NULL,
    ContactNumber VARCHAR(15),
    Email VARCHAR(100) UNIQUE NOT NULL,
    Address VARCHAR(255),
    HireDate DATE DEFAULT CURRENT_DATE,
    Department VARCHAR(50) NOT NULL,
	Profile VARCHAR(255) NOT NULL
)";

  if(!mysqli_query($conn,$sql))
  {
    die("Unable to create Faculty table");
  }


    //Faculty Academic table creation if not exists-------->
    $sql = "CREATE TABLE IF NOT EXISTS Faculty_Academic (
      AcademicID INT PRIMARY KEY AUTO_INCREMENT,
      FacultyID INT NOT NULL,
      passing ENUM('Yes', 'No') NOT NULL,
      Education ENUM('UGA', 'PGA','GRADUATED') NOT NULL,
      Qualifications VARCHAR(50) NOT NULL,
      FOREIGN KEY (FacultyID) REFERENCES Faculty(FacultyID)
      ON DELETE CASCADE
  )";
  
    if(!mysqli_query($conn,$sql))
    {
      die("Unable to create Faculty Academic table");
    }

    //CourseAssignments table creation if not exists-------->
//     $sql = "CREATE TABLE IF NOT EXISTS CourseAssignments (
//     AssignmentID INT PRIMARY KEY AUTO_INCREMENT,
//     FacultyID INT NOT NULL,
//     CourseID INT NOT NULL,
//     Semester VARCHAR(10) NOT NULL,
//     AcademicYear VARCHAR(9) NOT NULL,
//     FOREIGN KEY (FacultyID) REFERENCES Faculty(FacultyID)
//         ON DELETE CASCADE,
//     FOREIGN KEY (CourseID) REFERENCES Courses(CourseID)
//         ON DELETE CASCADE
// )";  
//       if(!mysqli_query($conn,$sql))
//       {
//         die("Unable to create CourseAssignments table");
//       }




        //FeeStructure table creation if not exists-------->
  $sql = "CREATE TABLE IF NOT EXISTS FeeStructure (
    FeeID INT PRIMARY KEY AUTO_INCREMENT,
    CourseID INT NOT NULL,
    FeeAmount DECIMAL(10,2) NOT NULL,
    AcademicYear VARCHAR(9) NOT NULL,
    FOREIGN KEY (CourseID) REFERENCES Courses(CourseID)
        ON DELETE CASCADE
)";
  if(!mysqli_query($conn,$sql))
  {
    die("Unable to create FeeStructure table");
  }

   //FeeStructure Manual Insertion---->
   $sql1="SELECT * FROM FeeStructure";
   $result=$conn->query($sql1);
   if($result->num_rows>0)
   {
     //echo "Fees Already Assign";
   }
   else{
     $sql_i1="INSERT INTO FeeStructure(FeeID,CourseID,FeeAmount,AcademicYear) VALUES (1,1,20500,'2025')";
     $sql_i2="INSERT INTO FeeStructure(FeeID,CourseID,FeeAmount,AcademicYear) VALUES (2,2,21500,'2025')";
     $sql_i3="INSERT INTO FeeStructure(FeeID,CourseID,FeeAmount,AcademicYear) VALUES (3,3,20500,'2025')";
     $sql_i4="INSERT INTO FeeStructure(FeeID,CourseID,FeeAmount,AcademicYear) VALUES (4,4,21500,'2025')";
     $sql_i5="INSERT INTO FeeStructure(FeeID,CourseID,FeeAmount,AcademicYear) VALUES (5,5,30500,'2025')";

     



     if($conn->query($sql_i1)==TRUE)
     {
       //echo "BSC IT COURSE FEES Added";
     }
     else{
       echo "Error To ADD Course BSCIT Fees";
     }

     if($conn->query($sql_i2)==TRUE)
     {
       //echo "MSC IT FEES ADDED";
     }
     else{
       echo "Error To ADD MSC it Fees";
     }

     if($conn->query($sql_i3)==TRUE)
     {
      // echo "BSC ICT  FEES ADDED";
     }
     else{
       echo "Error To ADD BSC ICT FEES";
     }

     if($conn->query($sql_i4)==TRUE)
     {
      // echo "MSC ICT FESS ADDED";
     }
     else{
       echo "Error To Add MSC ICT FEES";
     }

     if($conn->query($sql_i5)==TRUE)
     {
      // echo "OTHER FEES Added";
     }
     else{
       echo "Error To Add OTHER Course FEES";
     }
   }




    //StudentFees table creation if not exists-------->
    $sql = "CREATE TABLE IF NOT EXISTS StudentFees (
    FeeTransactionID INT PRIMARY KEY AUTO_INCREMENT,
    StudentID INT NOT NULL,
    CourseID INT NOT NULL,
    FeeAmount DECIMAL(10,2) NOT NULL,
    PaymentDate DATE DEFAULT CURRENT_DATE,
    PaymentStatus ENUM('Paid', 'Pending') NOT NULL,
    FOREIGN KEY (StudentID) REFERENCES Students(StudentID)
        ON DELETE CASCADE,
    FOREIGN KEY (CourseID) REFERENCES Courses(CourseID)
        ON DELETE CASCADE
)";
      if(!mysqli_query($conn,$sql))
      {
        die("Unable to create StudentFees table");
      }



 //UserRoles table creation if not exists-------->
 $sql = "CREATE TABLE IF NOT EXISTS UserRoles (
    RoleID INT PRIMARY KEY,
    RoleName VARCHAR(50) UNIQUE NOT NULL
)";

      if(!mysqli_query($conn,$sql))
      {
        die("Unable to create UserRoles table");
      }

    //UserRoles Manual Insertion---->
    $sql1="SELECT * FROM UserRoles";
    $result=$conn->query($sql1);
    if($result->num_rows>0)
    {
      //echo "UserRoles Already Assign";
    }
    else{
      $sql_i1="INSERT INTO UserRoles(RoleID,RoleName) VALUES (1,'Admin')";
      $sql_i2="INSERT INTO UserRoles(RoleID,RoleName) VALUES (2,'Faculty')";
      $sql_i3="INSERT INTO UserRoles(RoleID,RoleName) VALUES (3,'Student')";

      if($conn->query($sql_i1)==TRUE)
      {
        //echo "Admin Assign";
      }
      else{
        echo "Error To Assign Admin";
      }

      if($conn->query($sql_i2)==TRUE)
      {
        //echo "Faculty Assign";
      }
      else{
        echo "Error To Assign Faclty";
      }

      if($conn->query($sql_i3)==TRUE)
      {
       // echo "Student Assign";
      }
      else{
        echo "Error To Assign Student";
      }
    }
    


      //Users table creation if not exists-------->
 $sql = "CREATE TABLE IF NOT EXISTS Users (
    UserID INT PRIMARY KEY AUTO_INCREMENT,
    Username VARCHAR(50) UNIQUE NOT NULL,
    Email VARCHAR(100) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL,
    RoleID INT NOT NULL,
    CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (RoleID) REFERENCES UserRoles(RoleID)
        ON DELETE CASCADE
)";

      if(!mysqli_query($conn,$sql))
      {
        die("Unable to create Users table");
      }


      //by default admin record
      $sql1="SELECT * FROM Users WHERE RoleID=1";
      $result=$conn->query($sql1);
      if($result->num_rows>0)
      {
        //echo "UserRoles Already Assign";
      }
      else{
        $sql_i1="INSERT INTO Users(UserID,Username,Email,Password,RoleID) VALUES (1,'admin01','admin@gmail.com','admin@1234',1)";
  
        if($conn->query($sql_i1)==TRUE)
        {
          //echo "Admin ADDed";
        }
        else{
          echo "Error To Add Admin";
        }
      }

      //password reset table for users----------
 $sql="CREATE TABLE IF NOT EXISTS password_reset_codes (
        email VARCHAR(255),
        code VARCHAR(10),
        expires_at DATETIME
    )";

if(!mysqli_query($conn,$sql))
{
  die("Unable to create Password Reset table");
}
    
      //Admin  table ----------
      $sql="CREATE TABLE IF NOT EXISTS Admin (
        AdminID INT PRIMARY KEY AUTO_INCREMENT,
        FirstName VARCHAR(50) NOT NULL,
        LastName VARCHAR(50) NOT NULL,
        Gender ENUM('Male', 'Female', 'Other') NOT NULL,
        ContactNumber VARCHAR(15),
        Email VARCHAR(100) UNIQUE NOT NULL,
        Profile VARCHAR(200) NOT NULL
    )";

if(!mysqli_query($conn,$sql))
{
  die("Unable to create Admin table");
}

      //by default admin record
      $sql1="SELECT * FROM Admin";
      $result=$conn->query($sql1);
      if($result->num_rows>0)
      {
        //echo "Admin Already Assign";
      }
      else{
        $sql_i1="INSERT INTO Admin(FirstName,LastName,Gender,ContactNumber,Email,Profile) VALUES ('Admin','college','Male','1234567899','admin@gmail.com','../profile/admin_profile/admin.png')";
  
        if($conn->query($sql_i1)==TRUE)
        {
          //echo "Admin ADDed";
        }
        else{
          echo "Error To Add Admin";
        }
      }


      //Notice table ----------
      $sql="CREATE TABLE IF NOT EXISTS Notices (
      id INT AUTO_INCREMENT PRIMARY KEY,
      title VARCHAR(255) NOT NULL,
      description TEXT NOT NULL,
      notice_date DATE NOT NULL,
      publisher_name TEXT NOT NULL
      )";
      
      if(!mysqli_query($conn,$sql))
      {
        die("Unable to create Notice table");
      }
      
            //by default Notice record
            $sql1="SELECT * FROM Notices";
            $result=$conn->query($sql1);
            if($result->num_rows>0)
            {
              //echo "Notices Already Assign";
            }
            else{
              $sql_i1="INSERT INTO Notices (title, description, notice_date,publisher_name)
VALUES 
('New Exam Schedule Released', 'The revised exam timetable has been published...', '2024-04-08','abc'),
('Guest Lecture on Cloud Computing', 'Join us for a special lecture by industry experts...', '2024-04-05','abc');
";
        
              if($conn->query($sql_i1)==TRUE)
              {
                //echo "Notices ADDed";
              }
              else{
                echo "Error To Add Notices";
              }
            }


            
// -------- Subjects Table Creation if not exists -------->
$sql = "CREATE TABLE IF NOT EXISTS Subjects (
  SubjectID INT PRIMARY KEY AUTO_INCREMENT,
  CourseID INT NOT NULL,
  SubjectName VARCHAR(100) NOT NULL,
  FOREIGN KEY (CourseID) REFERENCES Courses(CourseID) ON DELETE CASCADE
)";
if (!mysqli_query($conn, $sql)) {
  die("Unable to create Subjects table");
}


// -------- Subjects Manual Insertion -------->
$sql1 = "SELECT * FROM Subjects";
$result = $conn->query($sql1);
if ($result->num_rows > 0) {
  // echo "Subjects Already Assigned";
} else {
  $sql_i1 = "INSERT INTO Subjects (CourseID, SubjectName) VALUES (1, 'Mathematics')";
  $sql_i2 = "INSERT INTO Subjects (CourseID, SubjectName) VALUES (1, 'C language')";
  $sql_i3 = "INSERT INTO Subjects (CourseID, SubjectName) VALUES (2, 'Object Oriented Programming')";
  $sql_i4 = "INSERT INTO Subjects (CourseID, SubjectName) VALUES (2, 'Cloud Computing')";
  $sql_i5 = "INSERT INTO Subjects (CourseID, SubjectName) VALUES (3, 'Asp.net')";
  $sql_i6 = "INSERT INTO Subjects (CourseID, SubjectName) VALUES (3, 'Java Programming')";
  $sql_i7 = "INSERT INTO Subjects (CourseID, SubjectName) VALUES (4, 'Machine Learning')";
  $sql_i8 = "INSERT INTO Subjects (CourseID, SubjectName) VALUES (4, 'Cyber Security')";
  $sql_i9 = "INSERT INTO Subjects (CourseID, SubjectName) VALUES (5, 'General Studies')";

  if ($conn->query($sql_i1) === TRUE) {
    // echo "Mathematics Added";
  } else {
    echo "Error To Add Mathematics";
  }

  if ($conn->query($sql_i2) === TRUE) {
    // echo "Computer Science Added";
  } else {
    echo "Error To Add C language";
  }

  if ($conn->query($sql_i3) === TRUE) {
    // echo "Advanced Programming Added";
  } else {
    echo "Error To Add Object Oriented Programming";
  }

  if ($conn->query($sql_i4) === TRUE) {
    // echo "Cloud Computing Added";
  } else {
    echo "Error To Add Cloud Computing";
  }

  if ($conn->query($sql_i5) === TRUE) {
    // echo "Discrete Maths Added";
  } else {
    echo "Error To Add Asp.net";
  }

  if ($conn->query($sql_i6) === TRUE) {
    // echo "Java Programming Added";
  } else {
    echo "Error To Add Java Programming";
  }

  if ($conn->query($sql_i7) === TRUE) {
    // echo "Machine Learning Added";
  } else {
    echo "Error To Add Machine Learning";
  }

  if ($conn->query($sql_i8) === TRUE) {
    // echo "Cyber Security Added";
  } else {
    echo "Error To Add Cyber Security";
  }

  if ($conn->query($sql_i9) === TRUE) {
    // echo "General Studies Added";
  } else {
    echo "Error To Add General Studies";
  }
}

  // -------- SubjectMarks Table Creation if not exists -------->
  $sql = "CREATE TABLE IF NOT EXISTS SubjectMarks (
    MarkID INT PRIMARY KEY AUTO_INCREMENT,
    StudentID INT NOT NULL,
    CourseID INT NOT NULL,
    SubjectID INT NOT NULL,
    MarksObtained DECIMAL(5,2) NOT NULL,
    FOREIGN KEY (StudentID) REFERENCES Students(StudentID) ON DELETE CASCADE,
    FOREIGN KEY (CourseID) REFERENCES Courses(CourseID) ON DELETE CASCADE,
    FOREIGN KEY (SubjectID) REFERENCES Subjects(SubjectID) ON DELETE CASCADE
  )";
  if (!mysqli_query($conn, $sql)) {
    die("Unable to create SubjectMarks table");
  }

      
?>
