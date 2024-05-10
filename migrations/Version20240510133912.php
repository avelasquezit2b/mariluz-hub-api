<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240510133912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking ADD created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE configuration ADD round_up_prices TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE hotel ADD booking_email VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE room_condition ADD supplement_type VARCHAR(25) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking DROP created_at');
        $this->addSql('ALTER TABLE configuration DROP round_up_prices');
        $this->addSql('ALTER TABLE hotel DROP booking_email');
        $this->addSql('ALTER TABLE room_condition DROP supplement_type');
    }
}
