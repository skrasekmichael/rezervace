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
    document.getElementById("name").value="";
    document.getElementById("surname").value="";
    document.getElementById("password").value="";
    document.getElementById("email").value="";
    document.getElementById("telNumber").value="";
    document.getElementById("level").value="";
        
    tableCreate.style.display="none";
    btnShow.style.display="inline";
}

function hideTableEvent(){
    var tableCreate = document.getElementById("tableEvent");
    var btnShow = document.getElementById("buttonEvent");
    document.getElementById("name").value="";
    document.getElementById("from").value="";
    document.getElementById("to").value="";
    document.getElementById("description").value="";
    document.getElementById("color").value="#ffffff";
        
    tableCreate.style.display="none";
    btnShow.style.display="inline";
}