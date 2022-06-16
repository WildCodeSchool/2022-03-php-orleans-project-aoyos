<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220616120933 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artist DROP FOREIGN KEY FK_1599687814A84C0');
        $this->addSql('DROP INDEX IDX_1599687814A84C0 ON artist');
        $this->addSql('ALTER TABLE artist DROP musical_style_id');
        $this->addSql('ALTER TABLE musical_style ADD artists_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE musical_style ADD CONSTRAINT FK_8D8EDF8E54A05007 FOREIGN KEY (artists_id) REFERENCES musical_style (id)');
        $this->addSql('CREATE INDEX IDX_8D8EDF8E54A05007 ON musical_style (artists_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artist ADD musical_style_id INT NOT NULL');
        $this->addSql('ALTER TABLE artist ADD CONSTRAINT FK_1599687814A84C0 FOREIGN KEY (musical_style_id) REFERENCES musical_style (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_1599687814A84C0 ON artist (musical_style_id)');
        $this->addSql('ALTER TABLE musical_style DROP FOREIGN KEY FK_8D8EDF8E54A05007');
        $this->addSql('DROP INDEX IDX_8D8EDF8E54A05007 ON musical_style');
        $this->addSql('ALTER TABLE musical_style DROP artists_id');
    }
}
