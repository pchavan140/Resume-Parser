  <!DOCTYPE html>
  <html>
  <head>
    <title>Upload And Parse Resume</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  </head>
<body>
    <div style="padding: 30px" align="right">
        <a href="view.php" class="btn btn-success btn-lg"><div class="button">Back To Previous Page</div></a>
    </div>
</body>

  </html>
  <?php
      function api($text){
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, 'https://gateway-fra.watsonplatform.net/natural-language-understanding/api/v1/analyze?version=2018-09-21');
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, "{\n \"text\": \" $text\",\n \"features\": {\n \"entities\": {\"model\":\"b42dfd36-d351-4ea3-85b0-88d41bcb8e8d\"}\n }\n}");
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_USERPWD, 'apikey' . ':' . '4kVgJLrbKVVQnkDZnfB29u_P9tUDLBlCMWP1vqAsftMx');
          $headers = array();
          $headers[] = 'Content-Type: application/json';
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
          $result = curl_exec($ch);
          if (curl_errno($ch)) {
              echo 'Error:' . curl_error($ch);
          }   
          $myArray = json_decode($result, true);
          include 'database.php';
          echo "<table class=table table-striped><th>Attribute</th><th>Value</th>";
            $name="";
            $add="";
            $mob="";
            $lang="";
            $mail="";
            $wrk="";
            $cert="";
            $quali="";

          for ($i = 0; $i < sizeof($myArray['entities']); $i++){
              $type = $myArray['entities'][$i]['type'];
              $text = $myArray['entities'][$i]['text'];
            
              if($type=="Name")$name.=$text.',';   
              else if($type=="address")$add.=$text.',';  
              else if($type=="mobile") $mob=$text;
              else if($type=="languages")$lang.=$text.',';
              else if($type=="email") $mail=$text;
              else if($type=="work") $wrk=$text;
              else if($type=="certifications") {$cert=$text;}          
              echo "<tr><td>".$type."</td><td>".$text."</td></tr>";  
          }
          $query="insert into resume_data(name,mobile_no,email,address,qualification,work,certifications,languages) values ('".$name."','".$mob."','".$mail."','".$add."','" .$quali."','" .$wrk."','" .$cert."','" .$lang."')";
            $sql=pg_query($query);
        echo "</table>";
        curl_close ($ch);
      }
      //echo "<script>setTimeout(\"location.href = 'view.php';\",0);</script>";



    $resume=""; 
    $resume=$_FILES["resume"]["name"];;
    $target_dir = "Uploads/";
    $target_dir = $target_dir . basename($_FILES["resume"]["name"]);
    if(move_uploaded_file($_FILES["resume"]["tmp_name"], $target_dir)){ 
      echo '<script>alert("Resume has been uploaded")</script>';
    
    include_once('class.pdf2text.php');

    $filename=$_FILES["resume"]["name"];;
    $a = new PDF2Text();
    $folder="Uploads/";
      $a->setFilename("./". $folder . $filename);
      $a->decodePDF();
      $text = $a->output();
      echo "<br><h3 align=center>Resume Text</h3><br>";
      $text = preg_replace("/[^a-zA-Z0-9\s:\,\.]/", "", $text);
      echo $text;
      echo "<br><br><h3 align=center>Resume parsed</h3>";
      echo api($text);
    }
  ?>

