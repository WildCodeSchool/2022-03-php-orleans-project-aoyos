<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220616085328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artist ADD musical_style_id INT NOT NULL, ADD artist_name VARCHAR(255) NOT NULL, ADD equipment VARCHAR(255) NOT NULL, ADD message VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE artist ADD CONSTRAINT FK_1599687814A84C0 FOREIGN KEY (musical_style_id) REFERENCES musical_style (id)');
        $this->addSql('CREATE INDEX IDX_1599687814A84C0 ON artist (musical_style_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artist DROP FOREIGN KEY FK_1599687814A84C0');
        $this->addSql('DROP INDEX IDX_1599687814A84C0 ON artist');
        $this->addSql('ALTER TABLE artist DROP musical_style_id, DROP artist_name, DROP equipment, DROP message');
    }
}
