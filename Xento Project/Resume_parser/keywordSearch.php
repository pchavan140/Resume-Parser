<!DOCTYPE html>
<html>
<head>
    <title>Keyword Search</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

</head>
<body>
    <div style="padding: 30px" align="right">
        <a href="admin_panel.php" class="btn btn-success btn-lg"><div class="button">Back To Previous Page</div></a>
    </div>
</body>
</html>
<?php 
    if(isset($_POST['searchKeyword'])){
        callSearch();
    }
    error_reporting(0);
    function callSearch(){
        include 'database.php';
        $keyword=$_POST["keyword"];
        echo "<h3 align=center>You have searched for <b>".$keyword."</b></h3>";
        //$records=pg_query($objConnection,"select * from resume_data");
        //$rows = pg_num_rows($records);
        //echo $rows;
        //$k=0;
        //while($k<$rows){
            $result=pg_query($objConnection,"select * from resume_data where name='".$keyword."' or languages like '%".$keyword."%' or mobile_no='".$keyword."' or email='".$keyword."' or address='".$keyword."' or qualification='".$keyword."' or work='".$keyword."' or certifications='".$keyword."'");
            $i=0;
            echo "<br><div align=center><h3><b>Person Details</b></h3></div><br><table border=1 class=table table-bordered><th>Name</th><th>Mobile No.</th><th>Email ID</th><th>Address</th><th>Qualification</th><th>Work</th><th>Certifications</th><th>Languages</th><tr>";
            $row = pg_fetch_row($result);
            $rowLength=count($row);
            //echo $rowLength;
            while($i<$rowLength) {
                echo "<td>".$row[$i]."</td>";
                $i++;
            }
            //$k++;    
        //} 
        echo "</tr></table>";
        pg_close($objConnection);    
    }
?>