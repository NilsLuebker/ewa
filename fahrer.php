<?php
require_once 'classes/Page.php';
require_once 'classes/FahrerListItem.php';

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
		$getLieferStatus = $this->_database->prepare("SELECT Status, Adresse, BestellungID, GROUP_CONCAT(PizzaName) as Pizzen, SUM(Preis) as Gesamtpreis FROM Bestellung JOIN (BestelltePizza JOIN Angebot on PizzaNummer = fPizzanummer) on fBestellungID = BestellungID WHERE NOT EXISTS(SELECT * FROM BestelltePizza where fBestellungID = BestellungID and Status in ('bestellt', 'im_ofen', 'geliefert')) GROUP BY BestellungID");
		$getLieferStatus->execute();
		$result = $getLieferStatus->get_result();
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$this->listItems[] = new FahrerListItem(
					htmlspecialchars($row["Adresse"]),
					htmlspecialchars($row["Pizzen"]),
					htmlspecialchars($row["Status"]),
					htmlspecialchars($row["BestellungID"]),
					htmlspecialchars($row["Gesamtpreis"])
				);
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
		if(empty($this->listItems)) {
			echo
<<<HTML
				<li class="lieferstatus-item"><p>Keine Bestellungen bereit fuer die Auslieferung</p></li>
HTML;
		} else {
			foreach($this->listItems as $pizza) {
				$pizza->generateView();
			}
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
