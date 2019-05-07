<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190507175436 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE prestations DROP FOREIGN KEY FK_B338D8D1A70A18EC');
        $this->addSql('DROP INDEX IDX_B338D8D1A70A18EC ON prestations');
        $this->addSql('ALTER TABLE prestations CHANGE entreprises_id entreprise_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE prestations ADD CONSTRAINT FK_B338D8D1A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprises (id)');
        $this->addSql('CREATE INDEX IDX_B338D8D1A4AEAFEA ON prestations (entreprise_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE prestations DROP FOREIGN KEY FK_B338D8D1A4AEAFEA');
        $this->addSql('DROP INDEX IDX_B338D8D1A4AEAFEA ON prestations');
        $this->addSql('ALTER TABLE prestations CHANGE entreprise_id entreprises_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE prestations ADD CONSTRAINT FK_B338D8D1A70A18EC FOREIGN KEY (entreprises_id) REFERENCES entreprises (id)');
        $this->addSql('CREATE INDEX IDX_B338D8D1A70A18EC ON prestations (entreprises_id)');
    }
}
