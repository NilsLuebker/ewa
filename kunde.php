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
		$this->generatePageHeader("Pizzaservice - Kunde");
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
		if($_SERVER['REQUEST_METHOD'] != 'POST') return;
		/* var_dump($_POST); */
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
