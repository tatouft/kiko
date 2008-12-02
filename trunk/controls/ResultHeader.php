
<link rel="stylesheet" href="css/ResultHeader.css" type="text/css">
<div class="Header">
	<div class="HeaderTitle"><? echo($headerTitle); ?></div>
	<div class="DivSearchBox">
		<div id="indicator1" style="display: none;">
		S
		</div>
		<div class="PreSearchBox"></div>
		<div class="SearchBox"><input type="text" id="FindInPrats"></div>
		<div class="PostSearchBox" onclick="FindPratiquantId($F('FindInPrats'));"></div>
		<div id="AutoCompleteSearch"></div>
	</div>
	<script type="text/javascript">
		function selected()
		{
			$('AutoCompleteSearch').style.opacity = 1;
		}
		new Ajax.Autocompleter("FindInPrats", "AutoCompleteSearch", "services/completeName.php", {paramName: "name", afterUpdateElement: selected});
	</script>
</div>