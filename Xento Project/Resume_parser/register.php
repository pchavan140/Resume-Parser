<?php
    include 'database.php';                
    $fullname=$_POST['name'];
    $mobile=$_POST['mobile'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    $query="insert into register values ('" .$fullname."','" .$mobile."','" .$email."','" .$password."')";
    $sql=pg_query($query);
    if(!$sql){
        echo '<script>alert("Not Registered")</script>';
        echo "<script>setTimeout(\"location.href = 'index.html';\",0);</script>";
    }
    else{
        echo '<script>alert("Successfully Registered.")</script>';
        echo "<script>setTimeout(\"location.href = 'index.html';\",0);</script>";
    }
    pg_close($objConnection);
?>
