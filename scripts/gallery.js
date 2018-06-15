class Gallery
{
	constructor()
	{
		this.images = [];
		this.index = 0;
		this.visible = false;
	}

	init(data)
	{
		this.images = data;
	}

	load()
	{
		if ($("#gallery").length == 0)
		{
			let gallery = "<div id='gallery'" + (this.visible ? " class='visible' " : "")  + ">";
			for (let i = -1; i <= 1; i++)
				gallery += "<div onclick='gallery.hide()' pos='" + i + "'><img alt='gallery image'></img></div>";
			gallery += "<div onclick='gallery.update(1)' class='to_left l'></div>";
			gallery += "<div onclick='gallery.update(-1)' class='to_right r'></div>";	
			$(gallery + "</div></div>").appendTo('body').submit();
		}

		this.set();
	}

	getindex(pos, index = this.index, max = this.images.length)
	{
		if (index + pos < 0)
			return max - 1
		else if (index + pos > max - 1)
			return 0;
		else 
			return index + pos;
	}

	set()
	{
		for (let i = -1; i <= 1; i++)
		{   
			let obj = $("#gallery div[pos='" + i + "'] img")[0];
			obj.setAttribute("src", this.images[this.getindex(i)]);
			obj.style.top = "calc(50% - " + (obj.offsetHeight / 2) + "px)";
			obj.style.left = "calc(50% - " + (obj.offsetWidth / 2) + "px)";
		}	
	}

	update(move)
	{
		let objs = [
			$("#gallery div[pos='-1']")[0], 
			$("#gallery div[pos='0']")[0], 
			$("#gallery div[pos='1']")[0]
		];
		for (let i = 0; i <= 2; i++)
		{
			objs[i].setAttribute("pos", this.getindex(move, i, 3) - 1);
		}	  
		
		this.index = this.getindex(move); 
		this.set();
	}

	show() { $("#gallery")[0].classList.add("visible"); }
	hide() { $("#gallery")[0].classList.remove("visible"); }

}

var gallery = new Gallery();
