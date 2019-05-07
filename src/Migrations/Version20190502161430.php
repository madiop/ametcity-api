<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190502161430 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE professionels (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, taux_horaire NUMERIC(10, 2) DEFAULT NULL, status TINYINT(1) DEFAULT NULL, description LONGTEXT DEFAULT NULL, experience INT DEFAULT NULL, UNIQUE INDEX UNIQ_2AB79852A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competences (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competences_professionels (competences_id INT NOT NULL, professionels_id INT NOT NULL, INDEX IDX_7F7E5B34A660B158 (competences_id), INDEX IDX_7F7E5B34E5E419F9 (professionels_id), PRIMARY KEY(competences_id, professionels_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE professionels ADD CONSTRAINT FK_2AB79852A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE competences_professionels ADD CONSTRAINT FK_7F7E5B34A660B158 FOREIGN KEY (competences_id) REFERENCES competences (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE competences_professionels ADD CONSTRAINT FK_7F7E5B34E5E419F9 FOREIGN KEY (professionels_id) REFERENCES professionels (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE competences_professionels DROP FOREIGN KEY FK_7F7E5B34E5E419F9');
        $this->addSql('ALTER TABLE competences_professionels DROP FOREIGN KEY FK_7F7E5B34A660B158');
        $this->addSql('DROP TABLE professionels');
        $this->addSql('DROP TABLE competences');
        $this->addSql('DROP TABLE competences_professionels');
    }
}
