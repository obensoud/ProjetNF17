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

CREATE TYPE jour AS ENUM ('lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche');


CREATE TABLE creneau (
	guide INTEGER REFERENCES guide(id_guide),
	expositionPermanente VARCHAR REFERENCES expositionPermanente(nom),
	jour jour,
	debutHoraire TIME,
	finHoraire TIME,
	CHECK (to_char(finHoraire-debutHoraire, 'HH:MI:SS') = '02:00:00'),
	PRIMARY KEY(guide, expositionPermanente, jour, debutHoraire)
	);
	
CREATE TABLE affecter (
	guide INTEGER REFERENCES guide(id_guide),
	expositionTemporaire VARCHAR REFERENCES expositionTemporaire(nom),
	PRIMARY KEY (guide, expositionTemporaire)
	);

CREATE TABLE museeExterieur (
	nom VARCHAR PRIMARY KEY,
	adresse VARCHAR
	);

CREATE TYPE oeuvre AS enum('peinture', 'sculpture', 'photographie');
	
CREATE TABLE typeOeuvre (
	nom oeuvre PRIMARY KEY
	);
	
	
CREATE TABLE restauration (
	id_restauration INTEGER PRIMARY KEY, 
	la_date TIMESTAMP,
	prix REAL,
	oeuvre INTERGER REFERENCES interne(id_Oeuvre) UNIQUE NOT NULL,
	prestataire VARCHAR(50) REFERENCES Prestataire(raisonSociale) NOT NULL,
	le_type VARCHAR(50) REFERENCES TypeRestauration(nom) UNIQUE, NOT NULL
	);
	
CREATE TABLE prestataire(
	nom VARCHAR(50) PRIMARY KEY,
	rasionSocial VARCHAR(50));

CREATE TABLE typeRestauration(
	nom VARCHAR(50) PRIMARY KEY);
	
CREATE TABLE pret(
	musee VARCHAR(50) REFERENCES MuseeExterieur(nom),
	interne INTERGER REFERENCES interne(id_Oeuvre),
	dateDebut TIMESTAMP,
	dateFin TIMESTAMP,
	PRIMARY KEY(musee, interne));

Create table Auteur(
	id_auteur int NOT NULL,
	nom VARCHAR(10),
	dateNaissance Date,
	dateMort Date,
	Unique(id_auteur)
);

create table interne(
	id_oeuvre int,
	titre varchar(10),
	jour Date,
	dimension Varchar(10),
	type TypeOeuvre NOT NULL,
	auteur Auteur,
	prix_acquisition float, 
	unique(id_oeuvre),
	exposition REFERENCES Expositionpermanente NOT NULL
);

						
Create table Externe (
	id_oeuvre  int,					
	titre  Varchar(10),
	jour : date,
	dimension  Varchar(10),
	id_auteur int NOT NULL,
	type REFERENCES TypeOeuvre,
	exposition REFERENCES ExpositionTemporaire,
	FOREIGN KEY (id_auteur) Auteur Utilisateur(id_auteur)			
);
					
Create table Emprunt (
	musee REFERENCES MuseeExterieur,						
	externe REFERENCES Externe, 
	dateDebut : date,
	dateFin : date	,
	Unique(musee,externe)					
) ;

