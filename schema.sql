CREATE TABLE expositionTemporaire(
	nom VARCHAR PRIMARY KEY,
	dateDebut date,
	dateFin date);

CREATE TABLE expositionPermanente(
	nom VARCHAR PRIMARY KEY);

CREATE TABLE salle(
	nom VARCHAR PRIMARY KEY,
	capaciteMax INTEGER,
	expositionTemp VARCHAR REFERENCES expositionTemporaire(nom) NOT NULL,
	expositionPer VARCHAR REFERENCES expositionPermanente(nom) NOT NULL,
	-- J'ajoute les NOT NULL pour implémenter les contraintes AVEC IN 
	CHECK ((expositionTemp IS NULL OR  expositionPer IS NULL)OR(expositionTemp IS NOT NULL   
		OR expositionPer IS NULL))
	);

CREATE TABLE panneau(
	numero INTEGER PRIMARY KEY,
	texte VARCHAR (255),
	salle VARCHAR (255),
	FOREIGN KEY (salle) REFERENCES salle(nom));

CREATE TABLE guide(
	id_guide INTEGER,
	nom VARCHAR,
	prenom VARCHAR,
	adresse VARCHAR,
	dateEbauche date);

CREATE TYPE day AS ENUM ('lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche');

CREATE TABLE jour(
	nom VARCHAR PRIMARY KEY);