<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240122154445 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hotel_hotel ADD CONSTRAINT FK_E087901DC360AD14 FOREIGN KEY (hotel_source) REFERENCES hotel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hotel_hotel ADD CONSTRAINT FK_E087901DDA85FD9B FOREIGN KEY (hotel_target) REFERENCES hotel (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hotel_hotel DROP FOREIGN KEY FK_E087901DC360AD14');
        $this->addSql('ALTER TABLE hotel_hotel DROP FOREIGN KEY FK_E087901DDA85FD9B');
    }
}
