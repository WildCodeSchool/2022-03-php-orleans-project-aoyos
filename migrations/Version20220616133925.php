<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220616133925 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE artist_musical_style (artist_id INT NOT NULL, musical_style_id INT NOT NULL, INDEX IDX_10B439CFB7970CF8 (artist_id), INDEX IDX_10B439CF814A84C0 (musical_style_id), PRIMARY KEY(artist_id, musical_style_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE artist_musical_style ADD CONSTRAINT FK_10B439CFB7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE artist_musical_style ADD CONSTRAINT FK_10B439CF814A84C0 FOREIGN KEY (musical_style_id) REFERENCES musical_style (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE artist ADD artist_name VARCHAR(255) NOT NULL, ADD equipment VARCHAR(255) NOT NULL, ADD message VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE artist_musical_style');
        $this->addSql('ALTER TABLE artist DROP artist_name, DROP equipment, DROP message');
    }
}
