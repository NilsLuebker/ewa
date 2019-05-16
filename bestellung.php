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
				$this->listItems[] = new PizzaListItem($row);
			}
		}
	}

	protected function generateView()
	{
		$this->getViewData();
		$this->generatePageHeader("Pizzaservice - Bestellung");
		echo
<<<HTML

		<main id="bestellung">
			<h1>Bestellung</h1>
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
			<form id="Warenkorb" action="kunde.php" method="POST">
				<h2>Warenkorb</h2>
				<select multiple name="pizzen[]" size="5" tabindex="0">
					<option value="margherita" selected>Margherita</option>
					<option value="salami" selected>Salami</option>
					<option value="tonno" selected>Tonno</option>
					<option value="prosciutto" selected>Prosciutto</option>
				</select>
				<p id="GesamtPreis">0 &euro;</p>
				<input type="text" placeholder="Ihre Adresse" name="adresse" tabindex="1" required/>
				<button onclick="gWarenkorb.deleteAll()" type="button" tabindex="2">Alles L&ouml;schen</button>
				<button onclick="gWarenkorb.deleteSelected()" type="button" tabindex="3">Auswahl L&ouml;schen</button>
				<button onclick="gWarenkorb.selectAll()" type="submit" tabindex="4">Bestellen</button>
			</form>

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
