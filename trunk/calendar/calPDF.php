<?
	//$htmlFile = "http://kobukai.be/calendar/cal.php";
	$htmlFile = "http://kobukai.be/calendar/test.html";

	topdf($htmlFile,"");
	
	function topdf($filename, $options = "") {
		# Tell HTMLDOC not to run in CGI mode...
        putenv("HTMLDOC_NOCGI=1");

		# Write the content type to the client...
		header("Content-Type: application/pdf");
		flush();

		# Run HTMLDOC to provide the PDF file to the user...
		passthru("../../cgi-bin/htmldoc -t pdf --quiet --jpeg --webpage $options '$filename'");
    }
?>