<?php
    include("config.php");

    $highscore="SELECT * FROM `nyertesstat` WHERE ID=(SELECT MAX(ID) FROM `nyertesstat`)";
    $eredmeny=$db_conn->query($highscore);
    $highsc=$eredmeny->fetch_assoc();
    $high="";
    $high.=$highsc["nyerteskarakter"] ."\r\n";
    $high.=$highsc["lepesszam"] ."\r\n";                //highscore lekérdezése
    $high.=$highsc["jatekkezdete"] ."\r\n";
    $high.=$highsc["jatekvege"];

    echo $high;
?>