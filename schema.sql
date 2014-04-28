CREATE TABLE salle(
	nom PRIMARY KEY,
	capaciteMax INTEGER,
	expositionTemporaire REFERENCES expositionTemporaire(nom),
	expositionPermanente REFERENCES expositionPermanente(nom)
	CHECK ((expositionTemporaire NULL OR expositionPermanente NULL)OR(expositionTemporaire NOT NULL OR expositionPermanente NULL));
CREATE TABLE panneau(
	numero INTEGER PRIMARY KEY,
	texte VARCHAR (255),
	salle REFERENCES salle(nom));

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
	nom PRIMARY KEY);
