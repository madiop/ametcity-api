<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190505020435 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE specialites (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE specialites_professionels (specialites_id INT NOT NULL, professionels_id INT NOT NULL, INDEX IDX_DFC8A8F15AEDDAD9 (specialites_id), INDEX IDX_DFC8A8F1E5E419F9 (professionels_id), PRIMARY KEY(specialites_id, professionels_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE specialites_professionels ADD CONSTRAINT FK_DFC8A8F15AEDDAD9 FOREIGN KEY (specialites_id) REFERENCES specialites (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE specialites_professionels ADD CONSTRAINT FK_DFC8A8F1E5E419F9 FOREIGN KEY (professionels_id) REFERENCES professionels (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE specialites_professionels DROP FOREIGN KEY FK_DFC8A8F15AEDDAD9');
        $this->addSql('DROP TABLE specialites');
        $this->addSql('DROP TABLE specialites_professionels');
    }
}
