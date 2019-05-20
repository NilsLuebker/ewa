<?php
class PizzaListItem {
	private $image_file_name;
	private $pizza_name;
	private $pizza_price;

	public function __construct($image_file_name, $pizza_name, $pizza_price) {
		$this->image_file_name = $image_file_name;
		$this->pizza_name = $pizza_name;
		$this->pizza_price = $pizza_price;
	}

	public function generateView() {
		echo
<<<HTML
						<li onclick="gWarenkorb.add('$this->pizza_name', $this->pizza_price)">
							<img src="images/$this->image_file_name" alt="$this->pizza_name">
							<p>$this->pizza_name</p>
							<p>$this->pizza_price &euro;</p>
						</li>

HTML;
	}
}
