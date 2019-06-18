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
		$sql = "SELECT PizzaName, Preis, Bilddatei FROM Angebot";
		$result = $this->_database->query($sql);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$this->listItems[] = new PizzaListItem(
					$row["Bilddatei"],
					$row["PizzaName"],
					$row["Preis"]
				);
			}
		}
	}

	protected function generateView()
	{
		$this->getViewData();
		$this->generatePageHeader("Pizzaservice - Bestellung", 'bestellung');
		echo
<<<HTML

		<main id="bestellung">
			<section id="speisekarte">
				<h2>Speisekarte</h2>
				<ul>

HTML;
		foreach ($this->listItems as $item) {
			$item->generateView();
		}
		echo
<<<HTML

				</ul>
			</section>
			<form id="bestell-form" action="./kunde.php" method="POST" onsubmit="gWarenkorb.validate(event)">
				<h2>Warenkorb</h2>
				<select id="Warenkorb" multiple name="pizzen[]" tabindex="0">
				</select>
				<p id="GesamtPreis">0.00 &euro;</p>
				<input oninput="gWarenkorb.addressInput(this.value)" type="text" placeholder="Ihre Adresse" name="adresse" tabindex="1" required/>
				<div>
					<button onclick="gWarenkorb.removeAll()" type="button" tabindex="2">Alles L&ouml;schen</button>
					<button onclick="gWarenkorb.removeSelected()" type="button" tabindex="3">Auswahl L&ouml;schen</button>
					<button id="BestellenButton" onclick="gWarenkorb.selectAll()" type="submit" tabindex="4">Bestellen</button>
				</div>
			</form>
		</main>
HTML;
		$this->generatePageFooter();
	}

	protected function processReceivedData()
	{
		parent::processReceivedData();
	}

	public static function main()
	{
		session_start();
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
