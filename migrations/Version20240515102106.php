<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240515102106 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE channel_hotel (id INT AUTO_INCREMENT NOT NULL, hotel_id INT DEFAULT NULL, channel_id INT DEFAULT NULL, code VARCHAR(50) DEFAULT NULL, channel_code VARCHAR(50) DEFAULT NULL, UNIQUE INDEX UNIQ_B47256413243BB18 (hotel_id), UNIQUE INDEX UNIQ_B472564172F5A1AA (channel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE channel_hotel ADD CONSTRAINT FK_B47256413243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id)');
        $this->addSql('ALTER TABLE channel_hotel ADD CONSTRAINT FK_B472564172F5A1AA FOREIGN KEY (channel_id) REFERENCES channel (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE channel_hotel DROP FOREIGN KEY FK_B47256413243BB18');
        $this->addSql('ALTER TABLE channel_hotel DROP FOREIGN KEY FK_B472564172F5A1AA');
        $this->addSql('DROP TABLE channel_hotel');
    }
}
