<?php
require_once './classes/Page.php';
class KundenStatus extends Page
{

	private $listItems = array();

	protected function __construct()
	{
		parent::__construct();
		// to do: instantiate members representing substructures/blocks
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
		header('Content-Type: application/json; charset=UTF-8');
		echo json_encode($this->listItems);
		/* $this->generatePageHeader('to do: change headline'); */
		/* $this->generatePageFooter(); */
	}

	protected function processReceivedData()
	{
		parent::processReceivedData();
	}

	public static function main()
	{
		session_start();
		try {
			$page = new KundenStatus();
			$page->processReceivedData();
			$page->generateView();
		}
		catch (Exception $e) {
			header("Content-type: text/plain; charset=UTF-8");
			echo $e->getMessage();
		}
	}
}

KundenStatus::main();
