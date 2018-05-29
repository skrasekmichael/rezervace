var isLogged = false;

//validace emailu
function check_email(email)
{
	let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

//rozšíření pro redirect stránky s POST požadavkem
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

//inicializace stránky
function init()
{
    tabpanels();
    gallery.load();
    dragobjects();
}

function dragobjects()
{
    let elems = $(".drag");
    for (let i = 0; i < elems.length; i++)
        drag_element(elems[i]);
}

//načtení všech tab panelů
function tabpanels()
{
    let panels = $(".tabpanel");
    //procházení všech tabpanelů
    for (let i = 0; i < panels.length; i++)
    {
        let panel = panels[i];
        panel.innerHTML += "<span class='default'></span><span class='cls'></span>"; //přidání spanu pro úpravu velikosti

        let index = panel.getAttribute("index"); //index výchozí tab záložky

        let _tabpanel = panel.getElementsByTagName("div");
        let p = [];

        for (let j = 0; j < _tabpanel.length; j++)
        {
            if (_tabpanel[j].classList.contains("tab") ||
                _tabpanel[j].classList.contains("title"))
                p.push(_tabpanel[j])             
        }

        let size = tabpanel(p, index); //načtení tab panelu

        //výchozí velikosti
        let w = panel.offsetWidth;
        let h = panel.offsetHeight;

        //pokud byly překročeny
        if (w < size[0])
            w = size[0];

        if (h < size[1])
            h = size[1];

        //nastavení velikosti
        panel.getElementsByClassName("default")[0].setAttribute("style", "margin-left: " + (-panel.offsetWidth) + "px; width: " + w + "px; height: " + h + "px;");
    }
}

function tabpanel(panel, index)
{
    let w = 0;
    let h = 0;

    //procházení všech tab záložek
    for (let i = 0; i < panel.length; i += 2)
    {
        let title = panel[i];
        //nastavení události
        title.onclick = function(){
            change_tab_panel(panel, i);
        };

        //pokud se jedná o právě zobrazovanou tab záložku
        if (i == index * 2)
            change_tab_panel(panel, i);

        //kontrola velikotí
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

//změna prohlížené tab záložky
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

//doplnění čísel před text
function to_len(val, len)
{
    val = val.toString();
    for (let i = 0; val.length < len; i++)
    {
        val = "0" + val;
    }
    return val;
}

//naformátovaní datumu from timestamp
function date_format(date, format)
{
    //Y-m-d H:i:s
    format = format.toLowerCase();

    format = format.replace("y", date.getFullYear());
    format = format.replace("m", to_len(date.getMonth() + 1, 2));
    format = format.replace("d", to_len(date.getDate(), 2));
    format = format.replace("h", to_len(date.getHours(), 2));
    format = format.replace("i", to_len(date.getMinutes(), 2));
    format = format.replace("s", to_len(date.getSeconds(), 2));

    return format;
}

function sleep(ms) 
{
    return new Promise(resolve => setTimeout(resolve, ms));
}
  
setLogged();
async function setLogged()
{
    ajax("isLogged", [0], function(yn, result){
        isLogged = result;
        console.log(result);
    });
    await sleep(100);
}

async function logOut()
{
    ajax("logOut", [0], function(yn, result){
        console.log(result);
    });
    await sleep(100);
    location.reload();
}

function ajax(fname, args, callback)
{
    $.ajax({
        type: "POST",
        url: 'ajax.php',
        dataType: 'json',
        data: { functionname: fname, arguments: args },

        success: function (obj, textstatus) 
        {
            if (callback != null)
            {
                if(!('error' in obj)) 
                {
                    return callback(true, obj.result);
                }
                else 
                {
                    return callback(false, obj.error);
                }
            }
        }
    });
}

var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
var drag_object;

function drag_element(element) 
{
    let e = $("#" + element.id + " .header")[0];
    e.onmousedown = function(e)
    {
        pos3 = e.clientX;
        pos4 = e.clientY;

        drag_object = element;
        document.onmousemove = move;
        document.onmouseup = up;
    }
}

function move(e)
{
    pos1 = pos3 - e.clientX;
    pos2 = pos4 - e.clientY;
    pos3 = e.clientX;
    pos4 = e.clientY;
    // set the element's new position:
    drag_object.style.top = (drag_object.offsetTop - pos2) + "px";
    drag_object.style.left = (drag_object.offsetLeft - pos1) + "px";
}

function up()
{
    document.onmousemove = null;
    document.onmouseup = null;
}