//nastaví rezervační údaje
function set(sport, field, start, index, fclass)
{
    //pokud je uživatel přihlášený
    if (isLogged)
    {
        let datetime = new Date(start * 1000);

        $("#reservation #table table tr td.hour").removeClass("selected"); //odstranění dříve označených časů
        let duration = $("input[name='duration']")[0].value;
        let cells = $("." + sport + " ." + fclass + " .hour");

        //označení časů
        for (let i = 0; i < duration; i++)
        {
            cells[index + i].classList.add("selected"); 
        }

        //nastavení údahů
        //čas
        let from = $("input[name='from']"); 
        for (let i = 0; i < from.length; i++)
            from[i].value = date_format(datetime, "Y-m-d H:i:s");

        //místo rezervace
        let places = $(".places");
        for (let i = 0; i < places.length; i++)
        {
            places[i].innerHTML = "<label name='sport'>" + sport + "</label> - <label name='field'>" + field + "</label>";
        }

        res_info(index, datetime, duration, places);
    }
}

//zobrazí info o rezervaci
function res_info(index, time, duration, places)
{
    let selected = $("#reservation #table table tr td.selected");
    for (let i = 0; i < selected.length; i++)
    {
        if (selected[i].classList.value == "hour selected")
        {
            $(".information #date span")[0].innerHTML = date_format(time, "Y-m-d");
        }
    }
}