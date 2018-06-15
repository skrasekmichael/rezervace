function select_avatar(index)
{
	$(".avatars li").removeClass("select");
	$(".avatars li")[index].classList.add("select");
	$("#avatar_index")[0].value = index;
}
