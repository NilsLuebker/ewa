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
