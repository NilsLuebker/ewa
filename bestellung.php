<?php
$html = <<<EOT
<!doctype html>
<html lang="de">
	<head>
		<meta charset="UTF-8"/>
		<link rel="stylesheet" href="style.css"/>
		<script src="javascript.js"></script>
		<title>Pizzaservice - Bestellung</title>
	</head>
	<body>
		<main id="bestellung">
			<h1>Bestellung</h1>
			<section id="speisekarte">
				<h2>Speisekarte</h2>
				<ul>
					<li>
						<img src="pizza.png" alt="">
						<p>Margherita</p>
						<p>4.00 &euro;</p>
					</li>
					<li>
						<img src="pizza.png" alt="">
						<p>Salami</p>
						<p>4.50 &euro;</p>
					</li>
					<li>
						<img src="pizza.png" alt="">
						<p>Hawaii</p>
						<p>5.50 &euro;</p>
					</li>
				</ul>
			</section>
			<form id="warenkorb" action="https://echo.fbi.h-da.de/" method="GET">
				<h2>Warenkorb</h2>
				<select multiple name="pizzen" size="3" tabindex="0">
					<option value="margherita" selected>Margherita</option>
					<option value="salami">Salami</option>
					<option value="hawaii">Hawaii</option>
				</select>
				<p>14.00 &euro;</p>
				<input type="text" placeholder="Ihre Adresse" name="adresse" tabindex="1"/>
				<button type="button" tabindex="2">Alles L&ouml;schen</button>
				<button type="button" tabindex="3">Auswahl L&ouml;schen</button>
				<button type="submit" tabindex="4">Bestellen</button>
			</form>
		</main>
	</body>
</html>
EOT;

echo $html;
