<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220615092101 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE artist ADD updated_at DATETIME NOT NULL, ADD musicalstyle LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', ADD equipment LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', ADD message LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE production CHANGE image image VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE artist DROP updated_at, DROP musicalstyle, DROP equipment, DROP message');
        $this->addSql('ALTER TABLE production CHANGE image image VARCHAR(255) DEFAULT NULL');
    }
}
