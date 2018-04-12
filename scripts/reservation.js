function set(sport, field, start, index, fclass)
{
    if (isLogged)
    {
        let datetime = new Date(start * 1000);

        $("#reservation #table table tr td.hour").removeClass("selected");
        let duration = $("input[name='duration']")[0].value;
        let cells = $("." + sport + " ." + fclass + " .hour");

        for (let i = 0; i < duration; i++)
        {
            cells[index + i].classList.add("selected"); 
        }

        let from = $("input[name='from']");
        for (let i = 0; i < from.length; i++)
            from[i].value = date_format(datetime, "Y-m-d H:i:s");

        let places = $(".places");
        for (let i = 0; i < places.length; i++)
        {
            places[i].innerHTML = "<label name='sport'>" + sport + "</label> - <label name='field'>" + field + "</label>";
        }

        res_info(index, datetime, duration, places);
    }
}

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