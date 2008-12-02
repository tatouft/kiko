function SetHidden(name,val)
{
	var o = (document.all)?document.all[name]:document.getElementById(name);
	
	o.value = val;
}
function SetAction(ac)
{
	SetHidden('action',ac);
}
function SetParam1(param)
{
	SetHidden('param1',param);
}

function Search(action, param1, param2)
{
	if(action == "all")
	{
		new Ajax.Updater($('PratiquantList'), 
						 "services/getPratiquants.php?action=all", {
						 evalScripts: true
						}
		);
	}
	if(action == "section")
	{
		url = "services/getPratiquants.php?action=section&section=" + param1;
		new Ajax.Updater($('PratiquantList'),					
						 url, {
						 evalScripts: true,
						 onFailure: function(transport) { 
							alert('oups');
							}
						 }		
		);
	}
}

function FindPratiquantId(search)
{
	url = "services/findPratiquantId.php?search=" + search;
	new Ajax.Request(url, {
					 method: 'get',
					 onSuccess: function(transport) {
					 Effect.Pulsate('PratRow' + transport.responseText, {pulses: 8, duration: 1.5 });
						}
					});
						 
}
