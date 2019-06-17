<?php 
    session_start();                   
    include 'database.php';

    $result=pg_query($objConnection,"select email,password from register WHERE email='" . $_POST["email"] . "' and password = '". $_POST["password"]."'");
    $row  = pg_fetch_object($result);
    if($row) {
        $_SESSION["id"] = $row->email;
        $_SESSION["password"] = $row->password;
        if($_POST["email"]=="admin@admin.com" && $_POST["password"]=="Admin@123"){
            header("Location:admin_panel.php");
        }
        else{
            header("Location:view.php");
        }
    } 
    else {
        echo '<script>alert("Please enter valid id password")</script>';
        echo "<script>setTimeout(\"location.href = 'index.html';\",0);</script>";
    }
    pg_close($objConnection);
?>