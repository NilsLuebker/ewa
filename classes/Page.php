<?php

abstract class Page
{
	protected $_database = null;

	protected function __construct()
	{
		$this->_database = new mysqli('localhost', 'root', '', 'pizzaservice');
		if ($this->_database->connect_errno) {
			echo 'Connection to the Database could not be created';
			exit;
		}
	}

	protected function __destruct()
	{
		$this->_database->close();
	}

	protected function generatePageHeader($headline = "", $autorefresh=False)
	{

		$headline = htmlspecialchars($headline);
		header("Content-type: text/html; charset=UTF-8");
		echo 
<<<HTML
<!doctype html>
<html lang="de">
	<head>
		<meta charset="UTF-8"/>
		<link rel="stylesheet" href="style.css"/>
		<script src="javascript.js"></script>
HTML;
		if($autorefresh){
			echo "<meta http-equiv=\"refresh\" content=\"5;URL='{$_SERVER['PHP_SELF']}'\">";
		}
		echo 
<<<HTML
		<title>$headline</title>
	</head>
	<body>
HTML;
	}

	protected function generatePageFooter()
	{
		$html_footer =
<<<HTML
	</body>
</html>
HTML;
	echo $html_footer;
	}

	protected function processReceivedData()
	{
		if (get_magic_quotes_gpc()) {
			throw new Exception
				("Bitte schalten Sie magic_quotes_gpc in php.ini aus!");
		}
	}
}
