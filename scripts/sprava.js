function changeTable(){
    if (document.location.href == "templates\sEvents.phtml"){ //here
        let newUrl="templates\sUsers.phtml";                  //here
        document.location.replace(newUrl);
    }else{
        let newUrl= "templates\sEvents.phtml";                //here
        document.location.replace(newUrl);
    }
}

function showTable(){
    var tableCreate = document.getElementById("tableCreate");
    var btnShow = document.getElementById("e_u");
    
    tableCreate.style.display="inline";
    btnShow.style.display="none";
}

function saveTableUser(){
    ChooseTable::saveUser;
    hideTableUser();
}

function hideTableUser(){
    var tableCreate = document.getElementById("tableCreate");
    var btnShow = document.getElementById("e_u");
    document.getElementById("name").value="Jm�no";
    document.getElementById("surname").value="P��jmen�";
    document.getElementById("password").value="Heslo";
    document.getElementById("email").value="Email";
    document.getElementById("telNumber").value="Telefonn� ��slo";
    document.getElementById("level").value="ID pr�va";
        
    tableCreate.style.display="none";
    btnShow.style.display="inline";
}

function saveTableEvent(){
    ChooseTable::saveEvent;
    hideTableEvent();
}

function hideTableEvent(){
    var tableCreate = document.getElementById("tableCreate");
    var btnShow = document.getElementById("e_u");
    document.getElementById("name").value="N�zev";
    document.getElementById("from").value="Od";
    document.getElementById("to").value="Do";
    document.getElementById("description").value="Popis";
    document.getElementById("color").value="HEX";
        
    tableCreate.style.display="none";
    btnShow.style.display="inline";
}