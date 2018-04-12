function check_email(email)
{
	let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

$.extend({
    redirectPost: function(location, args)
    {
        let form = '';
        $.each(args, function(key, value) {
            form += '<input type="hidden" name="' + key + '" value="' + value + '">';
        });
        $('<form action="' + location + '" method="POST">' + form + '</form>').appendTo('body').submit();
    }
});

window.onload = function()
{
    init();
}

function init()
{
    tabpanels();
}

function tabpanels()
{
    let panels = $(".tabpanel");
    for (let i = 0; i < panels.length; i++)
    {
        let panel = panels[i];
        panel.innerHTML += "<span class='default'></span><span class='cls'></span>";

        let index = panel.getAttribute("index");
        let size = tabpanel(panel.getElementsByTagName("div"), index);

        let w = panel.offsetWidth;
        let h = panel.offsetHeight;

        if (w < size[0])
            w = size[0];

        if (h < size[1])
            h = size[1];

        panel.getElementsByClassName("default")[0].setAttribute("style", "margin-left: " + (-panel.offsetWidth) + "px; width: " + w + "px; height: " + h + "px;");
    }
}

function tabpanel(panel, index)
{
    let w = 0;
    let h = 0;
    for (let i = 0; i < panel.length; i += 2)
    {
        let title = panel[i];
        title.onclick = function(){
            change_tab_panel(panel, i);
        };

        if (i == index * 2)
            change_tab_panel(panel, i);

        if (i + 1 < panel.length)
        {
            if (w < panel[i + 1].offsetWidth)
                w = panel[i + 1].offsetWidth;
            
            if (h < panel[i + 1].offsetHeight)
                h = panel[i + 1].offsetHeight;
        }
    }

    return [w, h];
}

function change_tab_panel(divs, index)
{
    for (let i = 0; i < divs.length; i++)
    {
        if (i == index)
        {
            divs[i].classList.add("selected");
            divs[i + 1].classList.add("selected");
            i++;
        }
        else
            divs[i].classList.remove("selected");
    }
}

function to_len(val, len)
{
    val = val.toString();
    for (let i = 0; val.length < len; i++)
    {
        val = "0" + val;
    }
    return val;
}

function date_format(date, format)
{
    //Y-m-d H:i:s
    format = format.toLowerCase();

    format = format.replace("y", date.getFullYear());
    format = format.replace("m", to_len(date.getMonth(), 2));
    format = format.replace("d", to_len(date.getDate(), 2));
    format = format.replace("h", to_len(date.getHours(), 2));
    format = format.replace("i", to_len(date.getMinutes(), 2));
    format = format.replace("s", to_len(date.getSeconds(), 2));

    return format;
}