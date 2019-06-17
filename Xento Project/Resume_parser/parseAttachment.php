<!DOCTYPE html>
<html>
<head>
    <title>Parse Resume</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>
    <div style="padding: 30px" align="right">
        <a href="admin_panel.php" class="btn btn-success btn-lg"><div class="button">Back To Previous Page</div></a>
    </div>
</body>
</html>
<?php
    include_once('class.pdf2text.php');
    set_time_limit(3000); 
    $hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
    $username = 'training.placement19@gmail.com';
    $password = 'project@123';
    /* try to connect */
    $inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());
    $emails = imap_search($inbox, 'SUBJECT "resume"');
    /* if any emails found, iterate through each email */
    if($emails) {
        $count = 1;
        /* put the newest emails on top */
        rsort($emails);
        /* for every email... */
        foreach($emails as $email_number) {
            /* get information specific to this email */
            $overview = imap_fetch_overview($inbox,$email_number,0);
            $message = imap_fetchbody($inbox,$email_number,2);
            /* get mail structure */
            $structure = imap_fetchstructure($inbox, $email_number);
            $attachments = array();

            /* if any attachments found... */
            if(isset($structure->parts) && count($structure->parts)) {
               for($i = 0; $i < count($structure->parts); $i++){
                    $attachments[$i] = array(
                        'is_attachment' => false,
                        'filename' => '',
                        'name' => '',
                        'attachment' => ''
                    );
                    if($structure->parts[$i]->ifdparameters) {
                        foreach($structure->parts[$i]->dparameters as $object) {
                            if(strtolower($object->attribute) == 'filename') {
                                $attachments[$i]['is_attachment'] = true;
                                $attachments[$i]['filename'] = $object->value;
                            }
                        }
                    }
                    if($structure->parts[$i]->ifparameters) {
                        foreach($structure->parts[$i]->parameters as $object) {
                           if(strtolower($object->attribute) == 'name') {
                                $attachments[$i]['is_attachment'] = true;
                                $attachments[$i]['name'] = $object->value;
                            }
                        }
                    }
                    if($attachments[$i]['is_attachment']) {
                        $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i+1);
                        /* 3 = BASE64 encoding */
                        if($structure->parts[$i]->encoding == 3){ 
                            $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                        }
                        /* 4 = QUOTED-PRINTABLE encoding */
                        elseif($structure->parts[$i]->encoding == 4) { 
                          $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                      }
                    }
                }
            }
            /* iterate through each attachment and save it */
            foreach($attachments as $attachment){
                if($attachment['is_attachment'] == 1){
                    $filename = $attachment['name'];
                    if(empty($filename)) $filename = $attachment['filename'];
                        if(empty($filename)) $filename = time() . ".dat";
                            $folder = "attachment";
                            if(!is_dir($folder)){
                                mkdir($folder);
                            }
                            $fp = fopen("./". $folder ."/". $email_number . "-" . $filename, "w+");
                            fwrite($fp, $attachment['attachment']);
                            fclose($fp);
                            $a = new PDF2Text();
                            $a->setFilename("./". $folder ."/". $email_number . "-" . $filename);
                            $a->decodePDF();
                            $text = $a->output();
                            echo "<br><h3 align=center>Resume Text</h3><br>";
                            $text = preg_replace("/[^a-zA-Z0-9\s:\,\.]/", "", $text);
                            echo $text;
                            echo "<br><br><h3 align=center>Resume parsed</h3>";
                            echo api($text);
                }
            }
        }
    } 

    /* close the connection */
    imap_close($inbox);
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
            
            if($type=="Name")$name.=$text.', ';   
            else if($type=="address")$add.=$text.', ';  
            else if($type=="mobile") $mob=$text;
            else if($type=="languages")$lang.=$text.', ';
            else if($type=="email") $mail=$text;
            else if($type=="work") $wrk=$text;
            else if($type=="certifications") {$cert=$text;}          
            echo "<tr><td>".$type."</td><td>".$text."</td></tr>";  
        }
        //echo $lang;
        //$lang = preg_replace("/[\,]/", " ", $lang);
        //echo $lang;
        $query="insert into resume_data(name,mobile_no,email,address,qualification,work,certifications,languages) values ('".$name."','".$mob."','".$mail."','".$add."','" .$quali."','" .$wrk."','" .$cert."','" .$lang."')";
        $sql=pg_query($query);
        echo "</table>";
        curl_close ($ch);
    }
    //echo "all attachment Downloaded";
?>