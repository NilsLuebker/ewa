<!doctype html>
<html lang="de">
	<head>
		<meta charset="UTF-8"/>
		<link rel="stylesheet" href="style.css"/>
		<script src="javascript.js"></script>
		<title>Pizzaservice - Fahrer</title>
	</head>
	<body>
		<main id="bestellung">
			<h1>Fahrer</h1>
			<form id="warenkorb" action="https://echo.fbi.h-da.de/" method="GET">
				<h2>Bestellungen</h2>
				<ul>
					<li>
                        <p>Schulz, Kasionostr. 5 13,50$</p>
                        <p>Margherita, Salami, Tonno</p>
                        <table>
                            <tr>
                                <th>fetig</th>
                                <th>unterwegs</th> 
                                <th>geliefert</th>
                            </tr>
                            <tr>
                                <td><input type="radio" name="schulz" value="fertig" checked></td> 
                                <td><input type="radio" name="schulz" value="unterwegs"></td>
                                <td><input type="radio" name="schulz" value="geliefert"></td>
                            </tr>
                        </table>
                    </li>
                    <li>
                        <p>Müller, Rheinstraße. 11 11,00$</p>
                        <p>Salami, Tonno</p>
                        <table>
                            <tr>
                                <th>fetig</th>
                                <th>unterwegs</th> 
                                <th>geliefert</th>
                            </tr>
                            <tr>
                                <td><input type="radio" name="mueller" value="fertig" checked></td> 
                                <td><input type="radio" name="mueller" value="unterwegs"></td>
                                <td><input type="radio" name="mueller" value="geliefert"></td>
                            </tr>
                        </table>
					</li>
				</ul>
				<button type="submit" tabindex="1">Aktuallisieren</button>
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
		$this->generatePageHeader("Pizzaservice - Baecker", True);
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
		foreach($this->listItems as $pizza) {
			$bestellt = $pizza['status'] == 'bestellt' ? 'checked' : '';
			$im_ofen = $pizza['status'] == 'im_ofen' ? 'checked' : '';
			$fertig = $pizza['status'] == 'fertig' ? 'checked' : '';
			if(empty($bestellt) || empty($im_ofen) || empty($fertig)){
			echo <<<HTML
			<tr>
				<td>{$pizza['name']}</td>
				<td><input type="radio" name="{$pizza['id']}" value="bestellt" $bestellt></td> 
				<td><input type="radio" name="{$pizza['id']}" value="im_ofen" $im_ofen></td>
				<td><input type="radio" name="{$pizza['id']}" value="fertig" $fertig></td>
			</tr>
HTML;
			}
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
