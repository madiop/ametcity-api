<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190424164817 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27BCF5E72D');
        $this->addSql('ALTER TABLE professionnel DROP FOREIGN KEY FK_7A28C10F57889920');
        $this->addSql('ALTER TABLE competences DROP FOREIGN KEY FK_DB2077CE8A49CC82');
        $this->addSql('ALTER TABLE realisations DROP FOREIGN KEY FK_FC5C476D8A49CC82');
        $this->addSql('ALTER TABLE professionnel DROP FOREIGN KEY FK_7A28C10FA76ED395');
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE clients');
        $this->addSql('DROP TABLE competences');
        $this->addSql('DROP TABLE devis');
        $this->addSql('DROP TABLE entreprises');
        $this->addSql('DROP TABLE fonction');
        $this->addSql('DROP TABLE gestionnaire_interne');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE professionnel');
        $this->addSql('DROP TABLE realisations');
        $this->addSql('DROP TABLE user');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom_categorie VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE clients (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL COLLATE utf8_unicode_ci, prenom VARCHAR(100) NOT NULL COLLATE utf8_unicode_ci, email VARCHAR(200) NOT NULL COLLATE utf8_unicode_ci, profession VARCHAR(50) NOT NULL COLLATE utf8_unicode_ci, sexe VARCHAR(50) NOT NULL COLLATE utf8_unicode_ci, telephone INT NOT NULL, adresse VARCHAR(100) NOT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE competences (id INT AUTO_INCREMENT NOT NULL, professionnel_id INT DEFAULT NULL, nom_competence VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, INDEX IDX_DB2077CE8A49CC82 (professionnel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE devis (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(100) NOT NULL COLLATE utf8_unicode_ci, date DATETIME NOT NULL, clients INT DEFAULT NULL, produits INT DEFAULT NULL, produit_entreprise INT DEFAULT NULL, services INT DEFAULT NULL, services_professionels INT DEFAULT NULL, UNIQUE INDEX UNIQ_8B27C52BBE2DDF8C (produits), UNIQUE INDEX UNIQ_8B27C52B7332E169 (services), UNIQUE INDEX UNIQ_8B27C52BC82E74 (clients), UNIQUE INDEX UNIQ_8B27C52BA421C36D (produit_entreprise), UNIQUE INDEX UNIQ_8B27C52B4BB112B0 (services_professionels), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE entreprises (id INT AUTO_INCREMENT NOT NULL, raison_sociale VARCHAR(150) NOT NULL COLLATE utf8_unicode_ci, presentation LONGTEXT NOT NULL COLLATE utf8_unicode_ci, adresse VARCHAR(256) NOT NULL COLLATE utf8_unicode_ci, telephone VARCHAR(15) NOT NULL COLLATE utf8_unicode_ci, fax VARCHAR(15) NOT NULL COLLATE utf8_unicode_ci, email VARCHAR(50) NOT NULL COLLATE utf8_unicode_ci, site VARCHAR(100) NOT NULL COLLATE utf8_unicode_ci, statut INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE fonction (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE gestionnaire_interne (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, fromId INT NOT NULL, toId INT NOT NULL, type TINYINT(1) NOT NULL, message LONGTEXT NOT NULL COLLATE utf8_unicode_ci, dateMessage DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, categorie_id INT NOT NULL, nomProduit VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, description VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, specifications JSON DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:json_array)\', photo VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, INDEX IDX_29A5EC27BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE professionnel (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, fonction_id INT NOT NULL, taux_horaires NUMERIC(10, 0) DEFAULT NULL, description LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, niveauDexperience INT DEFAULT NULL, publier TINYINT(1) DEFAULT NULL, INDEX IDX_7A28C10F57889920 (fonction_id), UNIQUE INDEX UNIQ_7A28C10FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE realisations (id INT AUTO_INCREMENT NOT NULL, professionnel_id INT DEFAULT NULL, desciption_realisation VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, photo VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, nom_projet VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, url VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, skills LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', deploye_at VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, INDEX IDX_FC5C476D8A49CC82 (professionnel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, prenom VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, email VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, password VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, roles LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', provider VARCHAR(225) DEFAULT NULL COLLATE utf8_unicode_ci, telephone VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, confirmationToken VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, photo LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, status INT DEFAULT NULL, dateEnregistrement DATETIME NOT NULL, adresse LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, civilite VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ville VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, pays VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE competences ADD CONSTRAINT FK_DB2077CE8A49CC82 FOREIGN KEY (professionnel_id) REFERENCES professionnel (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE professionnel ADD CONSTRAINT FK_7A28C10F57889920 FOREIGN KEY (fonction_id) REFERENCES fonction (id)');
        $this->addSql('ALTER TABLE professionnel ADD CONSTRAINT FK_7A28C10FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE realisations ADD CONSTRAINT FK_FC5C476D8A49CC82 FOREIGN KEY (professionnel_id) REFERENCES professionnel (id)');
        $this->addSql('DROP TABLE article');
    }
}
