CREATE DATABASE IF NOT EXISTS pizzaservice;

USE pizzaservice;

DROP TABLE IF EXISTS BestelltePizza;
DROP TABLE IF EXISTS Angebot;
DROP TABLE IF EXISTS Bestellung;

CREATE TABLE Angebot (
	PizzaNummer SERIAL,
	PizzaName VARCHAR(12) NOT NULL,
	Bilddatei VARCHAR(12) NOT NULL,
	Preis DECIMAL(15, 2) NOT NULL,
	PRIMARY KEY(PizzaNummer)
);

CREATE TABLE Bestellung (
	BestellungID SERIAL,
	Adresse VARCHAR(30) NOT NULL,
	Bestellzeitpunkt TIMESTAMP NOT NULL,
	PRIMARY KEY(BestellungID)
);

CREATE TABLE BestelltePizza (
	PizzaID SERIAL,
	fBestellungID BIGINT UNSIGNED NOT NULL,
	fPizzaNummer BIGINT UNSIGNED NOT NULL,
	Status ENUM('bestellt', 'im_ofen', 'fertig', 'unterwegs', 'geliefert'),
	CONSTRAINT `fk_bestellung`
		FOREIGN KEY (fBestellungID) REFERENCES Bestellung (BestellungID)
		ON DELETE CASCADE,
	CONSTRAINT `fk_pizza`
		FOREIGN Key (fPizzaNummer) REFERENCES Angebot (PizzaNummer)
		ON DELETE CASCADE,
	PRIMARY KEY (PizzaID)
);
