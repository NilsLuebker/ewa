<?php

abstract class Page
{
	protected $_database = null;

	protected function __construct()
	{
		$this->_database = new mysqli('localhost', 'nils', '1231', 'pizzaservice');
		if ($this->_database->connect_errno) {
			echo 'Connection to the Database could not be created';
			exit;
		}
	}

	protected function __destruct()
	{
		$this->_database->close();
	}

	protected function generatePageHeader($headline = "")
	{
		$headline = htmlspecialchars($headline);
		header("Content-type: text/html; charset=UTF-8");
		$html_head =
<<<HTML
<!doctype html>
<html lang="de">
	<head>
		<meta charset="UTF-8"/>
		<link rel="stylesheet" href="style.css"/>
		<script src="javascript.js"></script>
		<title>$headline</title>
	</head>
	<body>
HTML;
		echo $html_head;
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
