function showTableUser(){
    var tableCreate = document.getElementById("tableUser");
    var btnShow = document.getElementById("buttonUser");
    
    tableCreate.style.display="inline";
    btnShow.style.display="none";
}

function showTableEvent(){
    var tableCreate = document.getElementById("tableEvent");
    var btnShow = document.getElementById("buttonEvent");
    
    tableCreate.style.display="inline";
    btnShow.style.display="none";
}

function hideTableUser(){
    var tableCreate = document.getElementById("tableUser");
    var btnShow = document.getElementById("buttonUser");
    document.getElementById("name").value="Jméno";
    document.getElementById("surname").value="Příjmení";
    document.getElementById("password").value="Heslo";
    document.getElementById("email").value="Email";
    document.getElementById("telNumber").value="Telefonní číslo";
    document.getElementById("level").value="ID práva";
        
    tableCreate.style.display="none";
    btnShow.style.display="inline";
}

function hideTableEvent(){
    var tableCreate = document.getElementById("tableEvent");
    var btnShow = document.getElementById("buttonEvent");
    document.getElementById("name").value="Název";
    document.getElementById("from").value="Od";
    document.getElementById("to").value="Do";
    document.getElementById("description").value="Popis";
    document.getElementById("color").value="#ffffff";
        
    tableCreate.style.display="none";
    btnShow.style.display="inline";
}