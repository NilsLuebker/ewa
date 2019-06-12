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
		$getPizzaStatus = $this->_database->prepare("SELECT PizzaName, Status, fBestellungID, PizzaID FROM angebot, bestelltepizza WHERE PizzaNummer = fPizzanummer AND fBestellungID NOT IN (SELECT BestellungID FROM bestellung, bestelltepizza WHERE BestellungID = fBestellungID GROUP BY BestellungID HAVING 'fertig' = all(SELECT Status FROM bestelltepizza WHERE fBestellungID = BestellungID)) ORDER BY fBestellungID");
		$getPizzaStatus->execute();
		$result = $getPizzaStatus->get_result();
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$this->listItems[] = [
					"bid" => $row["fBestellungID"],
					"name" => $row["PizzaName"],
					"status" => $row["Status"],
					"id" => $row["PizzaID"]
				];
			}
		}
	}

	protected function generateView()
	{
		$this->getViewData();
		$this->generatePageHeader("Pizzaservice - Baecker", 'baecker', True);
		echo
<<<HTML
	<main id="bestellung">
			<h1>Bäcker</h1>
			<form id="speisekarte" action="baecker.php" method="POST">
				<h2>Bestellungen</h2>
				<table>
                    <tr>
                        <th></th>
                        <th>bestellt</th> 
                        <th>im Ofen</th>
                        <th>fertig</th>
                    </tr>
HTML;
		$lastBestellungId = -1;
		foreach($this->listItems as $pizza) {
			if($lastBestellungId == -1)
				$lastBestellungId = $pizza['bid'];
			$bestellt = $pizza['status'] == 'bestellt' ? 'checked' : '';
			$im_ofen = $pizza['status'] == 'im_ofen' ? 'checked' : '';
			$fertig = $pizza['status'] == 'fertig' ? 'checked' : '';
			if(empty($bestellt) && empty($im_ofen) && empty($fertig)) continue;
			if($pizza['bid'] != $lastBestellungId) {
				echo <<<HTML
			<tr>
				<td style="background: darkgrey"></td>
				<td style="background: darkgrey"></td>
				<td style="background: darkgrey"></td>
				<td style="background: darkgrey"></td>
			</tr>
HTML;
				echo <<<HTML
			<tr>
				<td>{$pizza['name']}</td>
				<td><input onclick="sendForm(event)" type="radio" name="{$pizza['id']}" value="bestellt" $bestellt></td>
				<td><input onclick="sendForm(event)" type="radio" name="{$pizza['id']}" value="im_ofen" $im_ofen></td>
				<td><input onclick="sendForm(event)" type="radio" name="{$pizza['id']}" value="fertig" $fertig></td>
			</tr>
HTML;
				$lastBestellungId = $pizza['bid'];
				continue;
			}
			echo <<<HTML
			<tr>
				<td>{$pizza['name']}</td>
				<td><input onclick="sendForm(event)" type="radio" name="{$pizza['id']}" value="bestellt" $bestellt></td> 
				<td><input onclick="sendForm(event)" type="radio" name="{$pizza['id']}" value="im_ofen" $im_ofen></td>
				<td><input onclick="sendForm(event)" type="radio" name="{$pizza['id']}" value="fertig" $fertig></td>
			</tr>
HTML;
		}
		echo
<<<HTML
			</table>
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
