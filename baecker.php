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
		$getPizzaStatus = $this->_database->prepare("SELECT PizzaName, Status, PizzaID FROM BestelltePizza JOIN Angebot ON PizzaNummer = fPizzaNummer");
		$getPizzaStatus->execute();
		$result = $getPizzaStatus->get_result();
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$this->listItems[] = [
					"name" => $row["PizzaName"],
					"status" => $row["Status"],
					"id"=>$row["PizzaID"]
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
HTML;
		foreach($this->listItems as $pizza) {
			$bestellt = $pizza['status'] == 'bestellt' ? 'checked' : '';
			$im_ofen = $pizza['status'] == 'im_ofen' ? 'checked' : '';
			$fertig = $pizza['status'] == 'fertig' ? 'checked' : '';
			echo
<<<HTML			
			<tr>
				<td>$pizza['name']</td>
				<td><input type="radio" name="$pizza['id']" value="bestellt" $bestellt></td> 
				<td><input type="radio" name="$pizza['id']" value="im_ofen" $im_ofen></td>
				<td><input type="radio" name="$pizza['id']" value="fertig" $fertig></td>
			</tr>
HTML;
		}
		echo
<<<HTML
			</table>
                <button type="submit" tabindex="1" accesskey="b">Aktualisieren</button>
            </form>
		</main>
HTML;
		$this->generatePageFooter();
	}

	protected function processReceivedData()
	{
		/*var_dump($_POST);
		parent::processReceivedData();
		$createBestellung = $this->_database->prepare("INSERT INTO Bestellung (Adresse) VALUES (?)");
		$createBestellung->bind_param('s', $address);
		$address = $this->_database->real_escape_string($_POST['adresse']);
		$createBestellung->execute();
		$bestellungID = $this->_database->insert_id;
		$getPizzaName = $this->_database->prepare("SELECT PizzaNummer FROM Angebot WHERE PizzaName = ?");
		$getPizzaName->bind_param('s', $pizzaName);
		$createBestelltePizza = $this->_database->prepare("INSERT INTO BestelltePizza (fBestellungID, fPizzaNummer) VALUES (?, ?)");
		/* var_dump($this->_database->error_list); 
		$createBestelltePizza->bind_param('ii', $bestellungID, $pizzaNummer);
		foreach($_POST['pizzen'] as $pizza) {
			$pizzaName = $this->_database->real_escape_string(ucfirst($pizza));
			$getPizzaName->execute();
			$row = $getPizzaName->get_result()->fetch_assoc();
			if($row) {
				$pizzaNummer = $row['PizzaNummer'];
				$createBestelltePizza->execute();
			}
		}*/
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
