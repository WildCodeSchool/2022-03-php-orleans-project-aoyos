<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220627111257 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reservation_musical_style (reservation_id INT NOT NULL, musical_style_id INT NOT NULL, INDEX IDX_63E2AFD3B83297E7 (reservation_id), INDEX IDX_63E2AFD3814A84C0 (musical_style_id), PRIMARY KEY(reservation_id, musical_style_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservation_musical_style ADD CONSTRAINT FK_63E2AFD3B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation_musical_style ADD CONSTRAINT FK_63E2AFD3814A84C0 FOREIGN KEY (musical_style_id) REFERENCES musical_style (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE reservation_musical_style');
    }
}
