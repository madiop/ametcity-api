<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190429130335 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_1483A5E9E7927C74 ON users');
        $this->addSql('ALTER TABLE users ADD is_active TINYINT(1) NOT NULL, DROP civilite, DROP adresse, DROP nom, DROP prenom, DROP email, DROP roles, DROP provider, DROP telephone, DROP confirmationToken, DROP ville, DROP pays, DROP photo, DROP status, DROP date_inscription');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users ADD civilite VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD adresse LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD nom VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD prenom VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD email VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD roles LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:array)\', ADD provider VARCHAR(225) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD telephone VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD confirmationToken VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD ville VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD pays VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD photo LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD status INT DEFAULT NULL, ADD date_inscription DATETIME NOT NULL, DROP is_active');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
    }
}
