var _index, _start, _duration, _places, _sport, _fclass;

window.onload = function()
{
    init();
    
    $("#duration:input").bind('keyup mouseup', function () {
        let duration = $(this).val()
        select(_index, _sport, _fclass, duration);
        res_info(_index, _start, duration, _places);
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
        let places = "<input type='hidden' name='sport' value='" + sport + "'><input type='hidden' name='field' value='" + field + "'>" + sport + " - " + field;
        $(".places")[0].innerHTML = places;

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

function myres(id, from, to, place, count, pop)
{
    $("#myres [name='id']")[0].value = id;
    $("#myres .from")[0].innerHTML = from;
    $("#myres .to")[0].innerHTML = to;
    $("#myres .place")[0].innerHTML = place;
    $("#myres .count")[0].innerHTML = count;
    $("#myres .for")[0].innerHTML = pop;
    show_message($("#myres")[0]);
}

function repeat_val_change(select)
{
    $("#rpindex")[0].value = select.selectedIndex;
    if (select.selectedIndex == 0)
        $("#rpnumber")[0].style.visibility = "visible";
    else
        $("#rpnumber")[0].style.visibility = "hidden";
}
