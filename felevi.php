<?php
    include("config.php");

    $coordx="";
    $coordy="";
    $char="";
    $tempido="";

    if($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        $coordx=$_GET["coordx"];
        $coordy=$_GET["coordy"];
        $char=$_GET["char"];
        $xmeret=$_GET["xmeret"];            //paraméterek
        $ymeret=$_GET["ymeret"];
        $tempido=$_GET["tempido"];
    }

    $sql_parancs="INSERT INTO `statisztika` (karakter, xcoord, ycoord, tempido) VALUES ('$char', $coordx, $coordy, $tempido)";      //berak minden lépést az adatbázisba

    if(mysqli_query($db_conn, $sql_parancs))
    {
        //echo "Sikerult";
    }
    else
    {
        //echo "Error. nem lehet teljesiteni" . mysqli_error($db_conn);
    }

    function getlepesszam($db_conn, $winkar)
    {
        $lepesxido="";
        $lepesoido="";

        $xeredmeny=$db_conn->query("SELECT COUNT(karakter) FROM `statisztika` WHERE karakter='x'");
        $oeredmeny=$db_conn->query("SELECT COUNT(karakter) FROM `statisztika` WHERE karakter='o'");     // lépésszámot kérdezi le
        $xlepes=$db_conn->query("SELECT SUM(tempido) FROM `statisztika` WHERE karakter='x'");           
        $olepes=$db_conn->query("SELECT SUM(tempido) FROM `statisztika` WHERE karakter='o'");           // a lépés idejét kéri le
        $xlepesidoszam=$xlepes->fetch_assoc();
        $olepesidoszam=$olepes->fetch_assoc();
        //$x=$xlepesidoszam["SUM(tempido)"];
        //$o=$olepesidoszam["SUM(tempido)"];
        if($xlepesidoszam["SUM(tempido)"] == 0)
        {
            $x=1;
        }
        else
        {
            $x=$xlepesidoszam["SUM(tempido)"];                  // ne kapjon csúnya errort (0-val osztás)
        }
        if($olepesidoszam["SUM(tempido)"] == 0)
        {
            $o=1;
        }
        else
        {
            $o=$olepesidoszam["SUM(tempido)"];
        }
        $xsor=$xeredmeny->fetch_assoc();
        //$lepesx=$xsor["COUNT(karakter)"];
        $osor=$oeredmeny->fetch_assoc();                    // tömböz
        //$lepeso=$osor["COUNT(karakter)"];
        if($xsor["COUNT(karakter)"] == 0)
        {
            $lepesx=1;
        }
        else
        {
            $lepesx=$xsor["COUNT(karakter)"];
        }
        if($osor["COUNT(karakter)"] == 0)
        {
            $lepeso=1;
        }
        else
        {
            $lepeso=$osor["COUNT(karakter)"];
        }
        $lepesxido=(int)($x)/(int)($lepesx);
        $lepesoido=(int)($o)/(int)($lepeso);            // lépésátlag

        if($winkar == "x")
        {
            echo "x lepesszama: ". $lepesx . "\r\n";
            echo "o lepesszama: ". $lepeso . "\r\n";
            echo "x lepesi ideje: ". $lepesxido . "\r\n";
            echo "o lepesi ideje: ". $lepesoido . "\r\n";
            $sql_nyertesx="UPDATE `nyertesstat` SET nyerteskarakter='$winkar' , lepesszam=$lepesx , jatekvege=CURRENT_TIME() WHERE ID=(SELECT MAX(ID) FROM `nyertesstat`)";
            if(mysqli_query($db_conn, $sql_nyertesx))
            {
                //echo "Sikerült";
            }
            else
            {
                //echo "Nem sikerült";
            }
        }
        if($winkar == "o")
        {
            echo "x lepesszama: ". $lepesx . "\r\n";
            echo "o lepesszama: ". $lepeso . "\r\n";
            echo "x lepesi ideje: ". $lepesxido . "\r\n";
            echo "o lepesi ideje: ". $lepesoido . "\r\n";
            $sql_nyerteso="UPDATE `nyertesstat` SET nyerteskarakter='$winkar' , lepesszam=$lepeso , jatekvege=CURRENT_TIME() WHERE ID=(SELECT MAX(ID) FROM `nyertesstat`)";
            if(mysqli_query($db_conn, $sql_nyerteso))
            {
                //echo "Sikerült";
            }
            else
            {
                //echo "Nem sikerült";
            }                                           //kiiratás és beillesztés a nyertesek adatbázisába
        }
    }

    $tomb=array();
    $osszesadat="SELECT karakter, xcoord, ycoord FROM `statisztika`";
    $lekerdez=$db_conn->query($osszesadat);

    if($lekerdez->num_rows > 0)
    {
        while($sor = $lekerdez->fetch_assoc())
        {
            //echo $sor["karakter"] . $sor["xcoord"] . $sor["ycoord"];
            array_push($tomb, $sor);                                        //feltöltöm a tömböt az adatbázis adataival
        }
    }

    $tombx=array();
    $tombo=array();

    for($i=0; $i<=$xmeret; $i++)
    {
        $tombtemp=array();
        for($j=0; $j<=$ymeret; $j++)
        {
            array_push($tombtemp, "?"); 
        }
        array_push($tombx, $tombtemp);              // 2 tömböt kibővítek 2-dimenzióssá
        array_push($tombo, $tombtemp);
    }

    for($i=0; $i<count($tomb); $i++)
    {
        if($tomb[$i]["karakter"] === "x")
        {
            $tombx[$tomb[$i]["xcoord"]][$tomb[(int)($i)]["ycoord"]]="x";
        }
        else if($tomb[$i]["karakter"] === "o")                                  // szétválogatom az x-eket és o-kat
        {
            $tombo[$tomb[$i]["xcoord"]][$tomb[(int)($i)]["ycoord"]]="o";
        }
    }

    $xe=false;
    $oe=false;

    //print_r($tombx);
    //print_r($tombo);

    $winkar="?";

    // x sor ellenőrzése
    for($i=0; $i<=$xmeret; $i++)
    {
        $xcount=0;
        for($j=0; $j<=$ymeret; $j++)
        {
            if($tombx[$i][$j]== "x")
            {
                $xe=true;
                if($xe==true)
                {
                    $xcount++;
                    if($xcount==5)
                    {
                        $winkar="x";
                    }
                }
            }
            else
            {
                $xe=false;
                $xcount=0;
            }
        }
    }

    //x oszlop ellenőrzése
    for($i=0; $i<=$ymeret; $i++)
    {
        $xcount=0;
        for($j=0; $j<=$xmeret; $j++)
        {
            if($tombx[$j][$i]== "x")
            {
                $xe=true;
                if($xe==true)
                {
                    $xcount++;
                    if($xcount==5)
                    {
                        $winkar="x";
                    }
                }
            }
            else
            {
                $xe=false;
                $xcount=0;
            }
        }
    }

    //o sor ellenőrzése
    for($i=0; $i<=$xmeret; $i++)
    {
        $ocount=0;
        for($j=0; $j<=$ymeret; $j++)
        {
            if($tombo[$i][$j]== "o")
            {
                $oe=true;
                if($oe==true)
                {
                    $ocount++;
                    if($ocount==5)
                    {
                        $winkar="o";
                    }
                }
            }
            else
            {
                $oe=false;
                $ocount=0;
            }
        }
    }

    //o oszlop ellenőrzése
    for($i=0; $i<=$ymeret; $i++)
    {
        $ocount=0;
        for($j=0; $j<=$xmeret; $j++)
        {
            if($tombo[$j][$i]== "o")
            {
                $oe=true;
                if($oe==true)
                {
                    $ocount++;
                    if($ocount==5)
                    {
                        $winkar="o";
                    }
                }
            }
            else
            {
                $oe=false;
                $ocount=0;
            }
        }
    }
    
    if(strcmp ($winkar, "?") !== 0)
    {   
        echo $winkar . " nyert \r\n";
        getlepesszam($db_conn, $winkar);    //meghívódik a fent található segédfüggvény

    }

    mysqli_close($db_conn);
?>