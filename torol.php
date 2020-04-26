<?php

    include("config.php");

    $sql_torol="DELETE FROM `statisztika`";
    /*$szo=$_GET["szo"];

    echo $szo;*/
    $sqlnyertestorol="DELETE FROM `nyertesstat` WHERE nyerteskarakter is NULL";

    $db_conn->query($sql_torol);            //törli azokat az adatokat, ahol nem fejeződött be a játék
    $db_conn->query($sqlnyertestorol);
?>