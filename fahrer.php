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
		$getLieferStatus = $this->_database->prepare("SELECT Status, Adresse, BestellungID, GROUP_CONCAT(PizzaName) as Pizzen, SUM(Preis) as Gesamtpreis FROM Bestellung JOIN (BestelltePizza JOIN Angebot on PizzaNummer = fPizzanummer) on fBestellungID = BestellungID GROUP BY BestellungID HAVING 'fertig' = ALL(SELECT Status FROM BestelltePizza WHERE fBestellungID = BestellungID) OR 'unterwegs' = ALL(SELECT Status FROM BestelltePizza WHERE fBestellungID = BestellungID) OR 'geliefert' = ALL(SELECT Status FROM BestelltePizza WHERE fBestellungID = BestellungID)");
		$getLieferStatus->execute();
		$result = $getLieferStatus->get_result();
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$this->listItems[] = [
					"adresse" => htmlspecialchars($row["Adresse"]),
					"pizzen" => htmlspecialchars($row["Pizzen"]),
					"status" => htmlspecialchars($row["Status"]),
					"id" => htmlspecialchars($row["BestellungID"]),
					"preis" => htmlspecialchars($row["Gesamtpreis"])
				];
			}
		}
	}

	protected function generateView()
	{
		$this->getViewData();
		$this->generatePageHeader("Pizzaservice - Fahrer", 'fahrer', True);
		echo
<<<HTML
	<main id="fahrer">
			<form id="lieferstatus" action="fahrer.php" method="POST">
				<h2>Bestellungen</h2>
				<ul>
HTML;
		foreach($this->listItems as $pizza) {
			$fertig = $pizza['status'] == 'fertig' ? 'checked' : '';
			$unterwegs = $pizza['status'] == 'unterwegs' ? 'checked' : '';
			$geliefert = $pizza['status'] == 'geliefert' ? 'checked' : '';
			//if(empty($bestellt) || empty($im_ofen) || empty($fertig)){
			echo <<<HTML
			<li>
				<p>{$pizza['adresse']} {$pizza['preis']}€</p>
				<p>{$pizza['pizzen']}</p>
				<table>
					<tr>
						<th>fetig</th>
						<th>unterwegs</th> 
						<th>geliefert</th>
					</tr>
					<tr>
						<td><input onclick="sendForm(event)" type="radio" name="{$pizza['id']}" value="fertig" $fertig></td> 
						<td><input onclick="sendForm(event)" type="radio" name="{$pizza['id']}" value="unterwegs" $unterwegs></td>
						<td><input onclick="sendForm(event)" type="radio" name="{$pizza['id']}" value="geliefert" $geliefert></td>
					</tr>
				</table>
</li>
HTML;
			}
		
		echo
<<<HTML
			</ul>
            </form>
		</main>
HTML;
		$this->generatePageFooter();
	}

	protected function processReceivedData()
	{
		if($_SERVER['REQUEST_METHOD'] != 'POST') return;
		//if(isset($_POST['']))
		/* var_dump($_POST); */
		//parent::processReceivedData();
		$updateStatus = $this->_database->prepare("UPDATE BestelltePizza SET Status = ? WHERE fBestellungID = ?");
		$updateStatus->bind_param('si', $status, $bestellungid);
		foreach($_POST as $bestellungid => $status){
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
