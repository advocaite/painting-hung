<div id="box1"><div id="box2"><h1>{129}</h1><br>
{list building}
<script language="JavaScript" type="text/javascript">
function show_build_list(list)
{
	var build_list = document.getElementById('build_list_'+list);
	var link = document.getElementById(list+'_link');	
	var all_link = document.getElementById('all_link');
	var soon_link = document.getElementById('soon_link');	
	var build_list_all = document.getElementById('build_list_all');
	var build_list_soon = document.getElementById('build_list_soon');	
	if (build_list.style.display == 'none')
	{
		build_list.style.display = '';
		if (link == soon_link)
		{
			link.innerHTML = '{75}';
			if (all_link !== null)
			{
				all_link.style.display = '';
			}
		}
		else
		{
			link.innerHTML = '{hide more}';
		}
	}
	else
	{
		build_list.style.display = 'none';
		if (link == soon_link)
		{
			link.innerHTML = '{73}';
			if (all_link !== null)
			{ 
				all_link.innerHTML = '{show more}';
				all_link.style.display = 'none';
				build_list_all.style.display = 'none';
			}
		}
		else
		{
			link.innerHTML = '{show more}';
		}
	} 
}
</script>
<div align="right"><a id="soon_link" href="javascript:show_build_list('soon');">{show1}</a></div><div id="build_list_soon" style="display: none;">{soon list building1}
</div></div></div></div>