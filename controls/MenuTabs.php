<link rel="stylesheet" href="css/MenuTabs.css" type="text/css">
<script type="text/javascript">
	function SwitchSearchTab(areaId, tab)
	{
	    //var m = document.getElementById('MenuTab').childNodes;
		var m = $('MenuTab').childNodes;
		for (i=0; i<m.length; i++) 
		{
			m[i].className = "Tab"; 
		}

	    var m = $('SearchArea').childNodes;
		for (i=0; i<m.length; i++) 
		{
			/*if(m.id == id)
				m[i].className = "SearchAreaContent"; 
			else*/
				m[i].className = "SearchAreaContent Invisible"; 
		}
		
		/*
		document.getElementById('AllArea').setAttribute('style', "display: none");
		document.getElementById('SectionArea').setAttribute('style', "display: none");
		document.getElementById('ExamensArea').setAttribute('style', "display: none");
		document.getElementById('ExpirationArea').setAttribute('style', "display: none");
		document.getElementById('UpArea').setAttribute('style', "display: none");*/

		tab.className = "Tab SelectedTab";
		$(areaId).setAttribute('class', "SearchAreaContent");
		//document.getElementById('AllArea').style = "display: none;";
		//document.getElementById('SectionArea').style = "display: none;";
		//document.getElementById('ExamensArea').style = "display: none;";
		//document.getElementById(id).style = "display: block;";
	}
	
</script>
<div id="MenuTab">
	<div class="Tab SelectedTab" onclick="JavaScript: SwitchSearchTab('AllArea', this);" id="AllTab">Tous</div>
	<div class="Tab" onclick="JavaScript: SwitchSearchTab('SectionArea', this);" id="SectionTab">Sections</div>
	<div class="Tab" onclick="JavaScript: SwitchSearchTab('ExamensArea', this);" id="ExamensTab">Examens</div>
	<div class="Tab" onclick="JavaScript: SwitchSearchTab('ExpirationArea', this);" id="ExpirationTab">Expirations</div>
	<div class="Tab" onclick="JavaScript: SwitchSearchTab('UpArea', this);" id="UpTab">Montées</div>
</div>