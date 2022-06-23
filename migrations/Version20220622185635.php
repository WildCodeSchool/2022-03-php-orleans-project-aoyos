<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220622185635 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partner ADD logo VARCHAR(255) DEFAULT NULL, ADD updated_at DATETIME NOT NULL, DROP image');
        $this->addSql('ALTER TABLE production ADD image_production VARCHAR(255) DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL, DROP image');
        $this->addSql('ALTER TABLE team_member ADD updated_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partner ADD image VARCHAR(255) NOT NULL, DROP logo, DROP updated_at');
        $this->addSql('ALTER TABLE production ADD image VARCHAR(255) NOT NULL, DROP image_production, DROP updated_at');
        $this->addSql('ALTER TABLE team_member DROP updated_at');
    }
}
