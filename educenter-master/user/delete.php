<?php

include_once '../user/connection.php';

$flag=0;
if (isset($_GET['table']) && isset($_GET['user_id']))  {
    $table=$_GET['table'];
    $user_id = $_GET['user_id'];

    
    if ($table == "faculty") {
        $column = "FacultyID";
    } elseif ($table == "students") {
        $column = "StudentID";
    }
    elseif ($table == "Notices") {
        $column = "id";
    }

    if($table == "students" || $table == "faculty")
    {
                //delete details from Users
                $user_email="SELECT Email From $table WHERE $column=$user_id";
                $result_email=$conn->query($user_email);
                if($result_email->num_rows===1)
                {
                    $row=$result_email->fetch_assoc();
                    $email=$row['Email'];
    
                    $delete_users="DELETE FROM Users WHERE Email='$email'";
                    if($conn->query($delete_users))
                    {
                        $flag=1;
                    }
                    else{
                        echo "Error To Delete in Users";
                        $flag=0;
    
                    }
    
                }
    }


                
    $sql = "DELETE FROM $table WHERE $column = $user_id";


    if (mysqli_query($conn, $sql)) {
        if($table=="faculty")//faculty table delete
        {
            $delete_AcademicDetails="DELETE FROM Faculty_Academic WHERE $column=$user_id";//delete Faculty details from AcademicDetails

            if($conn->query($delete_AcademicDetails))
            {
                $flag=1;
            }
            else{
                echo "Error To Delete in Faculty AcademicDetails";
                $flag=0;

            }


            if($flag==1)
            {
                header("Location:../faculty module/facultydis.php");
                exit();
            }
        }
        elseif($table=="students")//student table delete
        {
            $delete_AcademicDetails="DELETE FROM AcademicDetails WHERE $column=$user_id";//delete student details from AcademicDetails
            $delete_Enrollments="DELETE FROM Enrollments WHERE $column=$user_id";//delete student details from Enrollments

            if($conn->query($delete_AcademicDetails) && $conn->query($delete_Enrollments))
            {
                $flag=1;
            }
            else{
                echo "Error To Delete in AcademicDetails &  Enrollments";
                $flag=0;

            }

            //delete student details from RESULT
            $check_result="SELECT * FROM Results WHERE $column=$user_id";
            $result_check=$conn->query($check_result);

            if($result_check->num_rows>0)
            {
                $delete_result="DELETE FROM Results WHERE $column=$user_id";
                if($conn->query($delete_result))
                {
                    $flag=1;
                }
                else{
                    echo "Error To Delete in Results";
                    $flag=0;
                }

            }


            //delete student details from StudentFees
            $check_fees="SELECT * FROM StudentFees WHERE $column=$user_id";
            $result_fees=$conn->query($check_fees);

            if($result_fees->num_rows===1)
            {
                $delete_fess="DELETE FROM StudentFees WHERE $column=$user_id";
                if($conn->query($delete_fess))
                {
                    $flag=1;
                }
                else{
                    echo "Error To Delete in StudentFees";
                    $flag=0;

                }

            }

            if($flag==1)
            {
            header("Location:../student module/studentdis.php");
            exit();
            }
        }
        elseif($table=="Notices")//Notices table delete
        {
                $flag=1;

            if($flag==1)
            {
            header("Location:../admin/edit_notices.php");
            exit();
            }
        }
        
    } else {
        echo "Error deleting user.";
    }
}

mysqli_close($conn);
?>
