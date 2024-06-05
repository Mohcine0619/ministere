CREATE TABLE poles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    nom_directeur VARCHAR(255)
);

CREATE TABLE departements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    nom_directeur VARCHAR(255),
    id_pole INT,
    FOREIGN KEY (id_pole) REFERENCES poles(id)
);

CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    nom_chef VARCHAR(255),
    id_departement INT,
    FOREIGN KEY (id_departement) REFERENCES departements(id)
);

CREATE TABLE employes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    id_departement INT NULL,
    id_service INT NULL,
    role ENUM('rh', 'directeur', 'chef de service', 'employe') NOT NULL,
    photo_url VARCHAR(255),
    password VARCHAR(255) NOT NULL, -- Consider changing to hashed_password and storing hashes
    id_pole INT,
    FOREIGN KEY (id_pole) REFERENCES poles(id),
    FOREIGN KEY (id_departement) REFERENCES departements(id),
    FOREIGN KEY (id_service) REFERENCES services(id)
);

CREATE TABLE conges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_employe INT NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    statut VARCHAR(255) DEFAULT 'en attente',
    FOREIGN KEY (id_employe) REFERENCES employes(id)
);