<?php
  session_start();
?>
<?php
  if(isset($_SESSION["id"])) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Login</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>
  <div style="padding: 30px" align="right">
    <a href="logout.php" class="btn btn-success btn-lg"><div class="button">Logout</div></a>
  </div>
  <div align="center">
    <br><br>
    <h1>Welcome <?php echo $_SESSION["id"]; ?></h1>
    <br><br>
    <form name="send_mail" action="uploadPdf.php" enctype="multipart/form-data" autocomplete="off" method="post">
       <label>Upload Resume:</label><input type="file" name="resume"><br><br>
       <input class="btn btn-success btn-lg" type="submit" value="Upload" name="upload">
    </form>   
  </div>
</body>
<?php }else {
  echo '<script>alert("Please login first..")</script>';
  echo "<script>setTimeout(\"location.href = 'index.html';\",0);</script>";
} ?>
</html>