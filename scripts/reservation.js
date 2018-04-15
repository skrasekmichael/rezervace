var _index, _time, _duration, _places, _sport, _fclass;

window.onload = function()
{
    init();

    $("#duration:input").bind('keyup mouseup', function () {
        let duration = $(this).val()
        select(_index, _sport, _fclass, duration);
        res_info(_index, _time, duration, _places);
    });
}

//nastaví rezervační údaje
function set(sport, field, start, index, fclass)
{
    //pokud je uživatel přihlášený
    if (isLogged)
    {
        let datetime = new Date(start * 1000);

        let duration = $("input[name='duration']")[0].value;
        select(index, sport, fclass, duration);

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

        _index = index;
        _time = datetime;
        _places = places;
        _fclass = fclass;
        _sport = sport;
    }
}

function select(index, sport, fclass, duration)
{
    $("#reservation #table table tr td.hour").removeClass("selected"); //odstranění dříve označených časů
    let cells = $("." + sport + " ." + fclass + " .hour");

    //označení časů
    for (let i = 0; i < duration; i++)
    {
        cells[index + i].classList.add("selected"); 
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

/*
function check_datetime(datetime)
{
    let re = /^(([0]?[1-9]|1[0-2])/([0-2]?[0-9]|3[0-1])/[1-2]\d{3}) (20|21|22|23|[0-1]?\d{1}):([0-5]?\d{1})$/;
    return re.test(datetime)
}
... nefunguje
*/

