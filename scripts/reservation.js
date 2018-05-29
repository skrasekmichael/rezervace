var _index, _start, _duration, _places, _sport, _fclass;

window.onload = function()
{
    time_ragne();
    init();

    $("#duration:input").bind('keyup mouseup', function () {
        let duration = $(this).val()
        select(_index, _sport, _fclass, duration);
        res_info(_index, _start, duration, _places);
    });
}

function time_ragne()
{
    let range = "<div id='uic_time_from' class='drag'><div class='header'>from</div></di>";
    $("body")[0].innerHTML += range;
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
        let places = "<input type='hidden' name='sport' value='" + sport + "'><input type='hidden' name='field' value='" + field + "'>" + sport + " - " + field;
        $(".places")[0].innerHTML = places;
        

        res_info(index, start, duration, places);

        _index = index;
        _start = start;
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
function res_info(index, start, duration, places)
{
    let time = new Date(start * 1000);
    let selected = $("#reservation #table table tr td.selected");
    for (let i = 0; i < selected.length; i++)
    {
        console.log(selected[i].classList.value);
        if (selected[i].classList.value == "hour selected")
        {
            $(".information #date span")[0].innerHTML = date_format(time, "Y-m-d");
            $(".information #from span")[0].innerHTML = date_format(time, "H:i");
            $(".information #to span")[0].innerHTML = date_format(new Date(start * 1000 + 3600000 * duration / 2), "H:i");
            $(".information #place span")[0].innerHTML = places;

        }
    }
}

function repeat_val_change(select)
{
    console.log(select.selectedIndex);
    $("#rpindex")[0].value = select.selectedIndex;
    if (select.selectedIndex == 0)
        $("#rpnumber")[0].style.visibility = "visible";
    else
        $("#rpnumber")[0].style.visibility = "hidden";
}

/*
function check_datetime(datetime)
{
    let re = /^(([0]?[1-9]|1[0-2])/([0-2]?[0-9]|3[0-1])/[1-2]\d{3}) (20|21|22|23|[0-1]?\d{1}):([0-5]?\d{1})$/;
    return re.test(datetime)
}
... nefunguje
*/
