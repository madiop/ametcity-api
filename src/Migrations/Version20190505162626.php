<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190505162626 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE competences_professionels DROP FOREIGN KEY FK_7F7E5B34E5E419F9');
        $this->addSql('ALTER TABLE specialites_professionels DROP FOREIGN KEY FK_DFC8A8F1E5E419F9');
        $this->addSql('CREATE TABLE competences_professionnels (competences_id INT NOT NULL, professionnels_id INT NOT NULL, INDEX IDX_2F4FF7F5A660B158 (competences_id), INDEX IDX_2F4FF7F5E44D16E5 (professionnels_id), PRIMARY KEY(competences_id, professionnels_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE professionnels (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, tjm NUMERIC(10, 2) DEFAULT NULL, status TINYINT(1) DEFAULT NULL, description LONGTEXT DEFAULT NULL, experience INT DEFAULT NULL, UNIQUE INDEX UNIQ_8BCBFA5BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE specialites_professionnels (specialites_id INT NOT NULL, professionnels_id INT NOT NULL, INDEX IDX_C4E177395AEDDAD9 (specialites_id), INDEX IDX_C4E17739E44D16E5 (professionnels_id), PRIMARY KEY(specialites_id, professionnels_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE competences_professionnels ADD CONSTRAINT FK_2F4FF7F5A660B158 FOREIGN KEY (competences_id) REFERENCES competences (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE competences_professionnels ADD CONSTRAINT FK_2F4FF7F5E44D16E5 FOREIGN KEY (professionnels_id) REFERENCES professionnels (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE professionnels ADD CONSTRAINT FK_8BCBFA5BA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE specialites_professionnels ADD CONSTRAINT FK_C4E177395AEDDAD9 FOREIGN KEY (specialites_id) REFERENCES specialites (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE specialites_professionnels ADD CONSTRAINT FK_C4E17739E44D16E5 FOREIGN KEY (professionnels_id) REFERENCES professionnels (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE competences_professionels');
        $this->addSql('DROP TABLE professionels');
        $this->addSql('DROP TABLE specialites_professionels');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE competences_professionnels DROP FOREIGN KEY FK_2F4FF7F5E44D16E5');
        $this->addSql('ALTER TABLE specialites_professionnels DROP FOREIGN KEY FK_C4E17739E44D16E5');
        $this->addSql('CREATE TABLE competences_professionels (competences_id INT NOT NULL, professionels_id INT NOT NULL, INDEX IDX_7F7E5B34A660B158 (competences_id), INDEX IDX_7F7E5B34E5E419F9 (professionels_id), PRIMARY KEY(competences_id, professionels_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE professionels (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, tjm NUMERIC(10, 2) DEFAULT NULL, status TINYINT(1) DEFAULT NULL, description LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, experience INT DEFAULT NULL, UNIQUE INDEX UNIQ_2AB79852A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE specialites_professionels (specialites_id INT NOT NULL, professionels_id INT NOT NULL, INDEX IDX_DFC8A8F15AEDDAD9 (specialites_id), INDEX IDX_DFC8A8F1E5E419F9 (professionels_id), PRIMARY KEY(specialites_id, professionels_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE competences_professionels ADD CONSTRAINT FK_7F7E5B34A660B158 FOREIGN KEY (competences_id) REFERENCES competences (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE competences_professionels ADD CONSTRAINT FK_7F7E5B34E5E419F9 FOREIGN KEY (professionels_id) REFERENCES professionels (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE professionels ADD CONSTRAINT FK_2AB79852A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE specialites_professionels ADD CONSTRAINT FK_DFC8A8F15AEDDAD9 FOREIGN KEY (specialites_id) REFERENCES specialites (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE specialites_professionels ADD CONSTRAINT FK_DFC8A8F1E5E419F9 FOREIGN KEY (professionels_id) REFERENCES professionels (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE competences_professionnels');
        $this->addSql('DROP TABLE professionnels');
        $this->addSql('DROP TABLE specialites_professionnels');
    }
}
