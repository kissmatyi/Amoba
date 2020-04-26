var ido;
var counter=15;
function torol()
{
    document.getElementById("xmeret").value=""; 
    document.getElementById("ymeret").value=""; 
    document.getElementById("coordx").value=""; 
    document.getElementById("coordy").value=""; 
    document.getElementById("karakter").value="x";  // segít refreshnél kiüríteni a lapot
    ab_torol();           // törli a lépéses adatbázist minden táblagenerálásnál
}
function tablazat()
{
    var xmeret = document.getElementById("xmeret").value; 
    var ymeret = document.getElementById("ymeret").value;   // átveszi a tábla méretét

    var table = document.createElement("table");
    /*for(var i = 1; i <= xmeret; i++)
    {
        var row = document.createElement("tr");
        for(var j = 1; j <= ymeret; j++)
        {
            var elem = document.createElement("td");
            elem.setAttribute("id", "sor" + i + "oszlop"+ j);
            row.appendChild(elem);
        }
        table.appendChild(row);
    }*/
    for(var i = 0; i <= xmeret; i++)
    {
        var row = document.createElement("tr");
        for(var j = 0; j <= ymeret; j++)
        {
            var elem = document.createElement("td");
            elem.setAttribute("id", "sor" + i + "oszlop"+ j);
            if(i==0)
            {
                elem.innerHTML=j;
            }                                                   // táblázat generálás
            if(j==0)
            {
                elem.innerHTML=i;
            }
            row.appendChild(elem);
        }
        table.appendChild(row);
    }
    document.getElementById("table").appendChild(table);        //miután megvan a táblázat eltűnik
    document.getElementById("xmeret").style.display = "none";
    document.getElementById("tablagomb").style.display = "none";
    document.getElementById("ymeret").style.display = "none";
    ujjatekadatbbe();   // játék eleje kezdődik az adatbázisban
}
function berajzolas()
{
    var tempido=15-counter;
    clearInterval(ido);
    counter=15;
    time();
    var xmeret = document.getElementById("xmeret").value;
    var ymeret = document.getElementById("ymeret").value;
    var x = document.getElementById("coordx").value;
    var y = document.getElementById("coordy").value;
    var char = document.getElementById("karakter");     // koordináták átvétele html-ből
    var span = document.createElement("span");
    span.setAttribute("class", "xvagyo")
    if(parseInt(xmeret) < parseInt(x))
    {
        alert("Ez nem helyes koordináta!!")
    }
    if(parseInt(ymeret) < parseInt(y))                  // ellenőrzi a koordináták helyességét
    {
        alert("Ez nem helyes koordináta!!")
    }
    span.innerHTML = char.value;
    if(char.value == "o")
    {
        span.style.color = "blue";
    }                                                   // x és o külön színt kap
    else 
    {
        span.style.color = "red";
    }
    var id = "sor" + x + "oszlop" + y;
    //console.log(document.getElementById(id).innerText)
    if(document.getElementById(id).innerText=="")
    {
        document.getElementById(id).innerHTML = "";
        document.getElementById(id).appendChild(span);      //berajzolás
        lepes(x, y, char.value, xmeret, ymeret, tempido);
        if(char.value == "o")
        {
            char.selectedIndex = 0;
        }
        else
        {                                                   // koordinátaváltás
            char.selectedIndex = 1;
        }
    }
    else
    {
        alert("a hely foglalt")
    }
}

function ab_torol()
{
    var xhttp = new XMLHttpRequest()
    xhttp.onreadystatechange = function(){
        if (this.readyState == 4 && this.status == 200) {       //törli az adatbázist
            //console.log(this.responseText)
        }
    }
    xhttp.open("GET","torol.php",true);
    xhttp.send();
}

function lepes(x, y, char, xmeret, ymeret, tempido)
{
    var xhttp = new XMLHttpRequest()
    xhttp.onreadystatechange = function(){
        if (this.readyState == 4 && this.status == 200) 
        {
            if(this.responseText!="")
            {
                alert(this.responseText)                        //minden infot átad a php-nak
                ujjatekletrehoz();
            }
        }
    }
    xhttp.open("GET","felevi.php?coordx="+x+"&coordy="+y+"&char="+char+"&xmeret="+xmeret+"&ymeret="+ymeret+"&tempido="+tempido,true);
    xhttp.send();
}

function time()
{
    var char = document.getElementById("karakter");
    ido=setInterval(() => {
    if(counter > 0)
        {
            counter--;
            document.getElementById("ido").innerHTML= "Ido: " + counter;
        }
        else
        {
            if(char.value == "o")
            {
                char.selectedIndex = 0;
            }
            else                                        //timer
            {
                char.selectedIndex = 1;
            }
            counter=15;
        }  
    }, 1000);
}

function vanenyertes()
{
    var xhttp = new XMLHttpRequest()
    xhttp.onreadystatechange = function(){
        if (this.readyState == 4 && this.status == 200) {
            if(this.responseText!="?")
            {
                alert(this.responseText)                       // ellenőrzi hogy van-e nyertes
            }
        }
    }
    xhttp.open("GET","felevi.php",true);
    xhttp.send();
}

function ujjatekadatbbe()
{
    var xhttp = new XMLHttpRequest()
    xhttp.onreadystatechange = function(){
        if (this.readyState == 4 && this.status == 200) 
        {
            //console.log(this.responseText)                    // új játék
        }
    }
    xhttp.open("GET","ujjatek.php",true);
    xhttp.send();
}

function ujjatekletrehoz()
{
    torol();
    document.getElementById("table").innerHTML="";
    document.getElementById("xmeret").style.display = "inline-block";
    document.getElementById("tablagomb").style.display = "inline-block";
    document.getElementById("ymeret").style.display = "inline-block";         // visszarakja az eltűntetett blokkokat
    clearInterval(ido);
    counter=15;
    document.getElementById("ido").innerHTML= "Ido: "+15;
}

function highscore()
{
    var xhttp = new XMLHttpRequest()
    xhttp.onreadystatechange = function(){
        if (this.readyState == 4 && this.status == 200) 
        {
            alert(this.responseText)
        }                                                               // highscore
    }
    xhttp.open("GET","highscore.php",true);
    xhttp.send();
}