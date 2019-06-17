<?php
  $objConnection = pg_connect("host=localhost port=5433 dbname=resume_parser user=postgres password=root") or die('Could not connect: ' . pg_last_error());
      if (!$objConnection){
        die('Error: Could not connect: ' . pg_last_error());
      }
?>