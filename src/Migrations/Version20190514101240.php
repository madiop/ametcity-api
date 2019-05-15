<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190514101240 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users ADD professionnel_id INT DEFAULT NULL, ADD entreprise_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E98A49CC82 FOREIGN KEY (professionnel_id) REFERENCES professionnels (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprises (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E98A49CC82 ON users (professionnel_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9A4AEAFEA ON users (entreprise_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E98A49CC82');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9A4AEAFEA');
        $this->addSql('DROP INDEX UNIQ_1483A5E98A49CC82 ON users');
        $this->addSql('DROP INDEX UNIQ_1483A5E9A4AEAFEA ON users');
        $this->addSql('ALTER TABLE users DROP professionnel_id, DROP entreprise_id');
    }
}
