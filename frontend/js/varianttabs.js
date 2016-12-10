/*
 Tabs
 (c) 2009 By Xul.fr
 Freeware
*/


function loaditem( element)
{
	var container = document.getElementById('variantcontainer');
	container.src=element.rel;

	var tabs=document.getElementById('varianttabs').getElementsByTagName("a");
	for (var i=0; i < tabs.length; i++)
	{
		if(tabs[i].rel == element.rel) 
			tabs[i].className="selected";
		else
			tabs[i].className="";
	}
}

function startitem()
{
	var tabs=document.getElementById('varianttabs').getElementsByTagName("a");
	var container = document.getElementById('variantcontainer');
	container.src = tabs[0].rel;
}

window.onload=startitem;

