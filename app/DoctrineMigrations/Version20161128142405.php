<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161128142405 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Commande (idCommande INT AUTO_INCREMENT NOT NULL, prixCommande DOUBLE PRECISION NOT NULL, dateCommande DATE NOT NULL, utilisateurId INT DEFAULT NULL, etatId INT DEFAULT NULL, INDEX IDX_979CC42B31EE9377 (utilisateurId), INDEX IDX_979CC42B5EAF78A2 (etatId), PRIMARY KEY(idCommande)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE TypeFilm (idTypeFilm INT AUTO_INCREMENT NOT NULL, libelleTypeFilm VARCHAR(255) NOT NULL, PRIMARY KEY(idTypeFilm)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Realisateur (idRealisateur INT AUTO_INCREMENT NOT NULL, nomRealisateur VARCHAR(255) NOT NULL, PRIMARY KEY(idRealisateur)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Droit (idDroit INT AUTO_INCREMENT NOT NULL, libelleDroit VARCHAR(255) NOT NULL, PRIMARY KEY(idDroit)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Utilisateur (idUtilisateur INT AUTO_INCREMENT NOT NULL, password VARCHAR(255) NOT NULL, emailUtilisateur VARCHAR(255) NOT NULL, nomUtilisateur VARCHAR(255) NOT NULL, prenomUtilisateur VARCHAR(255) NOT NULL, adresseUtilisateur VARCHAR(255) NOT NULL, codePostalUtilisateur INT NOT NULL, VilleUtilisateur VARCHAR(255) NOT NULL, isActiveUtilisateur TINYINT(1) NOT NULL, uniqueKeyUtilisateur VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', PRIMARY KEY(idUtilisateur)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Film (idFilm INT AUTO_INCREMENT NOT NULL, titreFilm VARCHAR(255) NOT NULL, dureeFilm INT NOT NULL, dateFilm DATE NOT NULL, prixFilm DOUBLE PRECISION NOT NULL, quantiteFilm INT NOT NULL, imageFilm VARCHAR(255) NOT NULL, typeFilmId INT DEFAULT NULL, realisateurId INT DEFAULT NULL, INDEX IDX_2276111C224E7740 (typeFilmId), INDEX IDX_2276111C61E3D5F9 (realisateurId), PRIMARY KEY(idFilm)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Panier (idPanier INT AUTO_INCREMENT NOT NULL, quantitePanier INT NOT NULL, utilisateurId INT DEFAULT NULL, filmId INT DEFAULT NULL, INDEX IDX_236008C431EE9377 (utilisateurId), INDEX IDX_236008C4A1D6191D (filmId), PRIMARY KEY(idPanier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE EtatCommande (idEtatCommande INT AUTO_INCREMENT NOT NULL, libelleEtatCommande VARCHAR(255) NOT NULL, PRIMARY KEY(idEtatCommande)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Commande ADD CONSTRAINT FK_979CC42B31EE9377 FOREIGN KEY (utilisateurId) REFERENCES Utilisateur (idUtilisateur)');
        $this->addSql('ALTER TABLE Commande ADD CONSTRAINT FK_979CC42B5EAF78A2 FOREIGN KEY (etatId) REFERENCES EtatCommande (idEtatCommande)');
        $this->addSql('ALTER TABLE Film ADD CONSTRAINT FK_2276111C224E7740 FOREIGN KEY (typeFilmId) REFERENCES TypeFilm (idTypeFilm)');
        $this->addSql('ALTER TABLE Film ADD CONSTRAINT FK_2276111C61E3D5F9 FOREIGN KEY (realisateurId) REFERENCES Realisateur (idRealisateur)');
        $this->addSql('ALTER TABLE Panier ADD CONSTRAINT FK_236008C431EE9377 FOREIGN KEY (utilisateurId) REFERENCES Utilisateur (idUtilisateur)');
        $this->addSql('ALTER TABLE Panier ADD CONSTRAINT FK_236008C4A1D6191D FOREIGN KEY (filmId) REFERENCES Film (idFilm)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Film DROP FOREIGN KEY FK_2276111C224E7740');
        $this->addSql('ALTER TABLE Film DROP FOREIGN KEY FK_2276111C61E3D5F9');
        $this->addSql('ALTER TABLE Commande DROP FOREIGN KEY FK_979CC42B31EE9377');
        $this->addSql('ALTER TABLE Panier DROP FOREIGN KEY FK_236008C431EE9377');
        $this->addSql('ALTER TABLE Panier DROP FOREIGN KEY FK_236008C4A1D6191D');
        $this->addSql('ALTER TABLE Commande DROP FOREIGN KEY FK_979CC42B5EAF78A2');
        $this->addSql('DROP TABLE Commande');
        $this->addSql('DROP TABLE TypeFilm');
        $this->addSql('DROP TABLE Realisateur');
        $this->addSql('DROP TABLE Droit');
        $this->addSql('DROP TABLE Utilisateur');
        $this->addSql('DROP TABLE Film');
        $this->addSql('DROP TABLE Panier');
        $this->addSql('DROP TABLE EtatCommande');
    }
}
