function changeTable(){
    if (document.location.href == "C:\Users\User\Desktop\temata\rezervace\views\templates\sEvents.phtml"){
        let newUrl="C:\Users\User\Desktop\temata\rezervace\views\templates\sUsers.phtml";
        document.location.replace(newUrl);
    }else{
        let newUrl= "C:\Users\User\Desktop\temata\rezervace\views\templates\sEvents.phtml";
        document.location.replace(newUrl);
    }
}

function showTable(){
    let tableCreate = document.getElementById("tableCreate");
    let btnShow = document.getElementById("e_u");
    
    btnShow.onclick = function(){
        tableCreate.style.display="inline";
        btnShow.style.display="none";
    }
}

function hideTable(){
    let tableCreate = document.getElementById("tableCreate");
    let btnShow = document.getElementById("e_u");
    let btnCreate = document.getElementById("pridat");
    
    btnCreate.onclick = function(){
        tableCreate.style.display="none";
        btnShow.style.display="inline";
    }

}