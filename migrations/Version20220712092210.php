<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220712092210 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO musical_style (name) VALUES ("Généraliste"), ("Chanteur"), ("Soul"), 
        ("Musique DJ saxophoniste"), ("House"), ("Deep house"), ("Électro"), ("Pop/folk"), ("Musique swing"), 
        ("Rock"), ("Rap"),("Hip-Hop"), ("Groove"), ("Musique latino"), ("Funk");');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('TRUNCATE TABLE musical_style');
    }
}