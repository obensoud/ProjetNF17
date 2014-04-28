CREATE TABLE expositionTemporaire(
	nom VARCHAR PRIMARY KEY,
	dateDebut date,
	dateFin date);

CREATE TABLE expositionPermanente(
	nom VARCHAR PRIMARY KEY);



CREATE TABLE guide(
	id_guide INTEGER,
	nom VARCHAR,
	prenom VARCHAR,
	adresse VARCHAR,
	dateEbauche date);

CREATE TYPE day AS ENUM ('lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche');

CREATE TABLE jour(
	nom VARCHAR PRIMARY KEY);
