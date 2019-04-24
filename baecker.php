<!doctype html>
<html lang="de">
	<head>
		<meta charset="UTF-8"/>
		<link rel="stylesheet" href="style.css"/>
		<!-- <script src="javascript.js"></script> -->
		<title>Pizzaservice - Bäcker</title>
	</head>
	<body>
		<main id="bestellung">
			<h1>Bäcker</h1>
			<form id="speisekarte" action="https://echo.fbi.h-da.de/" method="GET">
				<h2>Bestellungen</h2>
				<table>
                    <tr>
                        <th></th>
                        <th>bestellt</th> 
                        <th>im Ofen</th>
                        <th>fertig</th>
                    </tr>
                    <tr>
                        <td>Margherita</td>
                        <td><input type="radio" name="margherita" value="bestellt" checked></td> 
                        <td><input type="radio" name="margherita" value="im_ofen"></td>
                        <td><input type="radio" name="margherita" value="fertig"></td>
                    </tr>
                    <tr>
                        <td>Tonno</td>
                        <td><input type="radio" name="tonno" value="bestellt"></td>
                        <td><input type="radio" name="tonno" value="im_ofen" checked></td>
                        <td><input type="radio" name="tonno" value="fertig"></td>
                        
                    </tr>
                    <tr>
                        <td>Prosciutto</td>
                        <td><input type="radio" name="prosciutto" value="bestellt"></td>
                        <td><input type="radio" name="prosciutto" value="im_ofen" checked></td>
                        <td><input type="radio" name="prosciutto" value="fertig"></td>
                    </tr>
                    <tr>
                        <td>Salami</td>
                        <td><input type="radio" name="salami" value="bestellt"></td>
                        <td><input type="radio" name="salami" value="im_ofen"></td>
                        <td><input type="radio" name="salami" value="fertig" checked></td>
                    </tr>
                </table>
                <button type="submit" tabindex="1" accesskey="b">Aktualisieren</button>
            </form>
		</main>
	</body>
</html>
<?php
require_once 'classes/Page.php';
require_once 'classes/PizzaListItem.php';

class BestellungPage extends Page
{

	private $listItems = array();

	protected function __construct()
	{
		parent::__construct();
	}

	protected function __destruct()
	{
		parent::__destruct();
	}

	protected function getViewData()
	{
		$getPizzaStatus = $this->_database->prepare("SELECT PizzaName, Status FROM BestelltePizza JOIN Angebot ON PizzaNummer = fPizzaNummer");
		$getPizzaStatus->execute();
		$result = $getPizzaStatus->get_result();
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$this->listItems[] = [
					"name" => $row["PizzaName"],
					"status" => $row["Status"]
				];
			}
		}
	}

	protected function generateView()
	{
		$this->getViewData();
		$this->generatePageHeader("Pizzaservice - Baecker");
		echo
<<<HTML
	<main id="bestellung">
		<h1>Kunde</h1>
		<section id="bestellstatus">
			<h2>Bestellstatus</h2>
HTML;
		foreach($this->listItems as $pizza) {
			echo "<p>{$pizza['name']}: {$pizza['status']}</p>";
		}
		echo
<<<HTML
			<a href="./bestellung.php">Neue Bestellung</a>
		</section>
	</main>
HTML;
		$this->generatePageFooter();
	}

	protected function processReceivedData()
	{
		var_dump($_POST);
		parent::processReceivedData();
		$createBestellung = $this->_database->prepare("INSERT INTO Bestellung (Adresse) VALUES (?)");
		$createBestellung->bind_param('s', $address);
		$address = $this->_database->real_escape_string($_POST['adresse']);
		$createBestellung->execute();
		$bestellungID = $this->_database->insert_id;
		$getPizzaName = $this->_database->prepare("SELECT PizzaNummer FROM Angebot WHERE PizzaName = ?");
		$getPizzaName->bind_param('s', $pizzaName);
		$createBestelltePizza = $this->_database->prepare("INSERT INTO BestelltePizza (fBestellungID, fPizzaNummer) VALUES (?, ?)");
		/* var_dump($this->_database->error_list); */
		$createBestelltePizza->bind_param('ii', $bestellungID, $pizzaNummer);
		foreach($_POST['pizzen'] as $pizza) {
			$pizzaName = $this->_database->real_escape_string(ucfirst($pizza));
			$getPizzaName->execute();
			$row = $getPizzaName->get_result()->fetch_assoc();
			if($row) {
				$pizzaNummer = $row['PizzaNummer'];
				$createBestelltePizza->execute();
			}
		}
	}

	public static function main()
	{
		try {
			$page = new BestellungPage();
			$page->processReceivedData();
			$page->generateView();
		}
		catch (Exception $e) {
			header("Content-type: text/plain; charset=UTF-8");
			echo $e->getMessage();
		}
	}
}

BestellungPage::main();
