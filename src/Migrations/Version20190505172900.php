<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190505172900 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE prestations (id INT AUTO_INCREMENT NOT NULL, produit_id INT NOT NULL, entreprises_id INT NOT NULL, prix_unitaire DOUBLE PRECISION NOT NULL, specifications LONGTEXT DEFAULT NULL, INDEX IDX_B338D8D1F347EFB (produit_id), INDEX IDX_B338D8D1A70A18EC (entreprises_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE prestations ADD CONSTRAINT FK_B338D8D1F347EFB FOREIGN KEY (produit_id) REFERENCES produits (id)');
        $this->addSql('ALTER TABLE prestations ADD CONSTRAINT FK_B338D8D1A70A18EC FOREIGN KEY (entreprises_id) REFERENCES entreprises (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE prestations');
    }
}
