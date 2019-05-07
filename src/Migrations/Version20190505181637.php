<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190505181637 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ligne_devis (id INT AUTO_INCREMENT NOT NULL, devis_id INT NOT NULL, prestation_id INT NOT NULL, quantite INT NOT NULL, montant_ht DOUBLE PRECISION NOT NULL, INDEX IDX_888B2F1B41DEFADA (devis_id), INDEX IDX_888B2F1B9E45C554 (prestation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ligne_devis ADD CONSTRAINT FK_888B2F1B41DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id)');
        $this->addSql('ALTER TABLE ligne_devis ADD CONSTRAINT FK_888B2F1B9E45C554 FOREIGN KEY (prestation_id) REFERENCES prestations (id)');
        $this->addSql('ALTER TABLE devis ADD total_ht DOUBLE PRECISION DEFAULT NULL, ADD total_ttc DOUBLE PRECISION DEFAULT NULL, ADD total_tva DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE prestations ADD tva DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE ligne_devis');
        $this->addSql('ALTER TABLE devis DROP total_ht, DROP total_ttc, DROP total_tva');
        $this->addSql('ALTER TABLE prestations DROP tva');
    }
}
