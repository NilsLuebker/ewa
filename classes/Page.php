<?php

abstract class Page
{
	protected $_database = null;
	private $pages = ['bestellung', 'kunde', 'baecker', 'fahrer'];

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

	protected function generatePageHeader($headline = "", $currentPage, $autorefresh=False)
	{

		$headline = htmlspecialchars($headline);
		header("Content-type: text/html; charset=UTF-8");
		echo 
<<<HTML
<!doctype html>
<html lang="de">
	<head>
		<meta charset="UTF-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" href="styles/main.css"/>
		<link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
		<script defer src="scripts/main.js"></script>
HTML;
		if($autorefresh){
			echo "<meta http-equiv=\"refresh\" content=\"5;URL='{$_SERVER['PHP_SELF']}'\">";
		}
		echo 
<<<HTML
		<title>$headline</title>
	</head>
	<body>
	<nav onclick="expandNavbar(event)">
		<ul id="nav-list">
HTML;
		foreach($this->pages as $page){
			$page_name = ucfirst($page);
			if($currentPage == $page)
				echo "<li><a class=\"currentPage\" href=\"./{$page}.php\">{$page_name}</a></li>";
			else
				echo "<li><a href=\"./{$page}.php\">{$page_name}</a></li>";
		}
	echo	
<<<HTML
		</ul>
	</nav>
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
