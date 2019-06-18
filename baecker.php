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
		$getPizzaStatus = $this->_database->prepare("SELECT PizzaName, Status, fBestellungID, PizzaID FROM Angebot, BestelltePizza as outer_BestelltePizza WHERE PizzaNummer = fPizzanummer AND NOT EXISTS(SELECT * FROM BestelltePizza WHERE fBestellungID = outer_BestelltePizza.fBestellungID AND Status in ('unterwegs', 'geliefert')) ORDER BY PizzaName");
		$getPizzaStatus->execute();
		$result = $getPizzaStatus->get_result();
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				if(!array_key_exists($row["fBestellungID"], $this->listItems)) {
					$this->listItems[$row["fBestellungID"]] = array();
				}
				array_push($this->listItems[$row["fBestellungID"]], [
					"name" => $row["PizzaName"],
					"status" => $row["Status"],
					"id" => $row["PizzaID"]
				]);
			}
			ksort($this->listItems);
		}
	}

	protected function generateView()
	{
		$this->getViewData();
		$this->generatePageHeader("Pizzaservice - Baecker", 'baecker', True);
		echo
<<<HTML
	<main id="baecker">
		<form id="baecker-bestellstatus" action="baecker.php" method="POST">
			<h2>Bestellungen</h2>
HTML;
		foreach ($this->listItems as $bestell_id => $pizza_data) {
			echo
<<<HTML
			<section class="baecker-bestellung">
				<h3>Bestellnummer: {$bestell_id}</h3>
				<div class="table-row">
					<div class="table-cell"></div>
					<div class="table-cell">bestellt</div>
					<div class="table-cell">im Ofen</div>
					<div class="table-cell">fertig</div>
				</div>
HTML;
			foreach ($pizza_data as $pizza) {
				$bestellt = $pizza['status'] == 'bestellt' ? 'checked' : '';
				$im_ofen = $pizza['status'] == 'im_ofen' ? 'checked' : '';
				$fertig = $pizza['status'] == 'fertig' ? 'checked' : '';
				echo
<<<HTML
			<div class="table-row">
				<div class="table-cell">{$pizza['name']}</div>
				<div class="table-cell"><input onclick="sendForm(event)" type="radio" name="{$pizza['id']}" value="bestellt" $bestellt></div>
				<div class="table-cell"><input onclick="sendForm(event)" type="radio" name="{$pizza['id']}" value="im_ofen" $im_ofen></div>
				<div class="table-cell"><input onclick="sendForm(event)" type="radio" name="{$pizza['id']}" value="fertig" $fertig></div>
			</div>
HTML;
			}
			echo "</section>";
		}
		echo
<<<HTML
            </form>
		</main>
HTML;
		$this->generatePageFooter();
	}

	protected function processReceivedData()
	{
		if($_SERVER['REQUEST_METHOD'] != 'POST') return;
		//if(isset($_POST['']))
		//var_dump($_POST);
		//parent::processReceivedData();
		$updateStatus = $this->_database->prepare("UPDATE BestelltePizza SET Status = ? WHERE PizzaID = ?");
		$updateStatus->bind_param('si', $status, $pizzaid);
		foreach($_POST as $pizzaid => $status){
			$updateStatus->execute();
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
