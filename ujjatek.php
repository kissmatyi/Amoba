<?php
    include("config.php");
    define('KEZDESIDO', date("Y-m-d H:i:s"));

    $kezdesido=KEZDESIDO;

    /*$sql="INSERT INTO `nyertesstat` (jatekkezdete) VALUES (".KEZDESIDO.")";
    if(mysqli_query($db_conn, $sql))
    {
        echo "lefut";
    }
    else
    {
        echo mysqli_error($db_conn);
    }*/
    $sql = $db_conn->prepare("INSERT INTO `nyertesstat` (jatekkezdete) VALUES (?)");
    $sql->bind_param("s",$kezdesido);
    $sql->execute();                        // kezdési idő kezdése
?>