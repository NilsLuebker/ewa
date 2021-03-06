<?php
class FahrerListItem {
	public $adresse;
	public $pizzen;
	public $status;
	public $id;
	public $preis;
	public $fertig;
	public $unterwegs;
	public $geliefert;

	public function __construct($adresse, $pizzen, $status, $id, $preis) {
		$this->adresse = $adresse;
		$this->pizzen = $pizzen;
		$this->status = $status;
		$this->id = $id;
		$this->preis = $preis;
		$this->fertig = $status == 'fertig' ? 'checked' : '';
		$this->unterwegs = $status == 'unterwegs' ? 'checked' : '';
		$this->geliefert = $status == 'geliefert' ? 'checked' : '';
		$pizzen = explode(",", $this->pizzen);
		$this->pizzen = array_count_values($pizzen);
		ksort($this->pizzen);
	}

	public function generateView() {
				echo
<<<HTML
				<li class="lieferstatus-item">
					<h3>Bestellnummer: {$this->id}</h3>
					<p>Adresse: {$this->adresse}</p>
					<p>Preis: {$this->preis}€</p>
					<ul>
HTML;
					foreach ($this->pizzen as $pizzz_name => $pizza_anzahl) {
						echo
<<<HTML
						<li><strong>{$pizza_anzahl}</strong> {$pizzz_name}</li>
HTML;
					}
				echo
<<<HTML
					</ul>
					<div>
						<div>
							<div>fetig</div>
							<div>unterwegs</div>
							<div>geliefert</div>
						</div>
						<div>
							<div><input onclick="sendForm(event)" type="radio" name="{$this->id}" value="fertig" {$this->fertig}></div>
							<div><input onclick="sendForm(event)" type="radio" name="{$this->id}" value="unterwegs" {$this->unterwegs}></div>
							<div><input onclick="sendForm(event)" type="radio" name="{$this->id}" value="geliefert" {$this->geliefert}></div>
						</div>
					</div>
				</li>
HTML;
	}
}
