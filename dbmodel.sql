CREATE DATABASE IF NOT EXISTS pizzaservice;

USE pizzaservice;

CREATE OR REPLACE TABLE Angebot (
	PizzaNummer SERIAL,
	PizzaName VARCHAR(12) NOT NULL,
	Bilddatei VARCHAR(12) NOT NULL,
	Preis DECIMAL(15, 2) NOT NULL,
	PRIMARY KEY(PizzaNummer)
);

CREATE OR REPLACE TABLE Bestellung (
	BestellungID SERIAL,
	Adresse VARCHAR(30) NOT NULL,
	Bestellzeitpunkt TIMESTAMP NOT NULL,
	PRIMARY KEY(BestellungID)
);

CREATE OR REPLACE TABLE BestelltePizza (
	PizzaID SERIAL,
	fBestellungID BIGINT UNSIGNED NOT NULL,
	fPizzaNummer BIGINT UNSIGNED NOT NULL,
	Status int,
	CONSTRAINT `fk_bestellung`
		FOREIGN KEY (fBestellungID) REFERENCES Bestellung (BestellungID)
		ON DELETE CASCADE,
	CONSTRAINT `fk_pizza`
		FOREIGN Key (fPizzaNummer) REFERENCES Angebot (PizzaNummer)
		ON DELETE CASCADE,
	PRIMARY KEY (PizzaID)
);
