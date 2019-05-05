<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190505142948 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE entreprises (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, raison_sociale VARCHAR(255) NOT NULL, fax VARCHAR(100) NOT NULL, status TINYINT(1) NOT NULL, siren VARCHAR(100) DEFAULT NULL, rcs_ville VARCHAR(100) DEFAULT NULL, code_naf VARCHAR(100) DEFAULT NULL, numero_tva VARCHAR(100) DEFAULT NULL, UNIQUE INDEX UNIQ_56B1B7A9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE entreprises ADD CONSTRAINT FK_56B1B7A9A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE users ADD code_postale INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE entreprises');
        $this->addSql('ALTER TABLE users DROP code_postale');
    }
}
