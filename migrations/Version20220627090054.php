<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220627090054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, identity_card VARCHAR(255) DEFAULT NULL, identity_photo VARCHAR(255) DEFAULT NULL, kbis VARCHAR(255) DEFAULT NULL, updated_at DATETIME NOT NULL, siret_number VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE artist ADD documents_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE artist ADD CONSTRAINT FK_15996875F0F2752 FOREIGN KEY (documents_id) REFERENCES document (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_15996875F0F2752 ON artist (documents_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artist DROP FOREIGN KEY FK_15996875F0F2752');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP INDEX UNIQ_15996875F0F2752 ON artist');
        $this->addSql('ALTER TABLE artist DROP documents_id');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }
}
