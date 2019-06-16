<?php
require_once 'classes/Page.php';
require_once 'classes/PizzaListItem.php';

class KundePage extends Page
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
		$getPizzaStatus = $this->_database->prepare("SELECT PizzaName, Status FROM BestelltePizza JOIN Angebot ON PizzaNummer = fPizzaNummer WHERE fBestellungID = ?");
		$getPizzaStatus->bind_param('s', $_SESSION['bestellung_id']);
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
		$this->generatePageHeader("Pizzaservice - Kunde", 'kunde');
		echo
<<<HTML
	<script defer src="scripts/status_update.js"></script>
	<main id="kunde">
		<section>
			<h2>Bestellstatus</h2>
			<ul id="bestellstatus">
HTML;
		if(empty($this->listItems)) {
			echo "<p>Keine bestellung aufgegeben</p>";
		} else {
			foreach($this->listItems as $pizza) {
				$name = htmlspecialchars($pizza['name']);
				$status = htmlspecialchars($pizza['status']);
				echo "<li>{$name}: </li>";
				echo "<li>{$status}</li>";
			}
		}
		echo
<<<HTML
			</ul>
			<button><a href="./bestellung.php">Neue Bestellung</a></button>
		</section>
	</main>
HTML;
		$this->generatePageFooter();
	}

	protected function processReceivedData()
	{
		if($_SERVER['REQUEST_METHOD'] != 'POST') return;
		if(!isset($_POST['adresse']) or !isset($_POST['pizzen'])) return;
		/* var_dump($_POST); */
		parent::processReceivedData();
		$createBestellung = $this->_database->prepare("INSERT INTO Bestellung (Adresse) VALUES (?)");
		$createBestellung->bind_param('s', $address);
		$address = $_POST['adresse'];
		$createBestellung->execute();
		$bestellungID = $this->_database->insert_id;
		$_SESSION['bestellung_id'] = $bestellungID;
		$getPizzaName = $this->_database->prepare("SELECT PizzaNummer FROM Angebot WHERE PizzaName = ?");
		$getPizzaName->bind_param('s', $pizzaName);
		$createBestelltePizza = $this->_database->prepare("INSERT INTO BestelltePizza (fBestellungID, fPizzaNummer) VALUES (?, ?)");
		$createBestelltePizza->bind_param('ii', $bestellungID, $pizzaNummer);
		foreach($_POST['pizzen'] as $pizza) {
			$pizzaName = ucfirst($pizza);
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
		session_start();
		try {
			$page = new KundePage();
			$page->processReceivedData();
			$page->generateView();
		}
		catch (Exception $e) {
			header("Content-type: text/plain; charset=UTF-8");
			echo $e->getMessage();
		}
	}
}

KundePage::main();
