<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190505173749 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE prestations ADD professionnel_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE prestations ADD CONSTRAINT FK_B338D8D18A49CC82 FOREIGN KEY (professionnel_id) REFERENCES professionnels (id)');
        $this->addSql('CREATE INDEX IDX_B338D8D18A49CC82 ON prestations (professionnel_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE prestations DROP FOREIGN KEY FK_B338D8D18A49CC82');
        $this->addSql('DROP INDEX IDX_B338D8D18A49CC82 ON prestations');
        $this->addSql('ALTER TABLE prestations DROP professionnel_id');
    }
}
