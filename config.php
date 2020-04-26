<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "felevi_webprog";

    $db_conn = new mysqli($servername, $username, $password, $dbname);      //konfiguráció

   /*if ($db_conn->connect_error) {
        die("Connection failed: " . $db_conn->connect_error);
    }
    echo "Connected successfully";*/
?>