<?php
  session_start();
?>
<?php
  if(isset($_SESSION["id"])) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Admin Panel</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>
  <div style="padding: 30px" align="right">
    <a href="logout.php" class="btn btn-success btn-lg"><div class="button">Logout</div></a>   
  </div>
  <div align="center">
    <h1><b>Welcome Admin!!!</b></h1><br>
    <h3><p>Click below to parse resumes which is received by email.</p></h3>
    <form name="parse" action="parseAttachment.php" method="post">
      <input class="btn btn-success btn-lg" type="submit" value="Parse Resumes" name="parse">
    </form>
    <br><br>  
  </div>
  <div align="center">
    <br>
    <h2><b>Keyword Search</b></h2>
      <form name="keyword" action="keywordSearch.php" method="post">
      <h3><span class="label label-primary">Keyword</span>&nbsp;&nbsp;<input type="text" name="keyword" placeholder="Enter keyword to search">
      <input class="btn btn-success btn-lg" type="submit" value="Search" name="searchKeyword">
    </form>
    <br><br><p>(Please confirm that before searching keyword you have already parsed the resumes.)</p>
  </div>
</body>
<?php }else {
  echo '<script>alert("Please login first..")</script>';
  echo "<script>setTimeout(\"location.href = 'index.html';\",0);</script>";
} ?>
</html>