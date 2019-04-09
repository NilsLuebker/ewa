<!doctype html>
<html lang="de">
	<head>
		<meta charset="UTF-8"/>
		<link rel="stylesheet" href="style.css"/>
		<!-- <script src="javascript.js"></script> -->
		<title>Pizzaservice - Bäcker</title>
	</head>
	<body>
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
                    <tr>
                        <td>Margherita</td>
                        <td><input type="radio" name="margherita" value="bestellt" checked></td> 
                        <td><input type="radio" name="margherita" value="im_ofen"></td>
                        <td><input type="radio" name="margherita" value="fertig"></td>
                    </tr>
                    <tr>
                        <td>Tonno</td>
                        <td><input type="radio" name="tonno" value="bestellt"></td>
                        <td><input type="radio" name="tonno" value="im_ofen" checked></td>
                        <td><input type="radio" name="tonno" value="fertig"></td>
                        
                    </tr>
                    <tr>
                        <td>Prosciutto</td>
                        <td><input type="radio" name="prosciutto" value="bestellt"></td>
                        <td><input type="radio" name="prosciutto" value="im_ofen" checked></td>
                        <td><input type="radio" name="prosciutto" value="fertig"></td>
                    </tr>
                    <tr>
                        <td>Salami</td>
                        <td><input type="radio" name="salami" value="bestellt"></td>
                        <td><input type="radio" name="salami" value="im_ofen"></td>
                        <td><input type="radio" name="salami" value="fertig" checked></td>
                    </tr>
                </table>
                <button type="submit" tabindex="1" accesskey="b">Aktualisieren</button>
            </form>
		</main>
	</body>
</html>
