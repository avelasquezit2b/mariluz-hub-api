<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240515151556 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE channel_hotel ADD hotel_id INT DEFAULT NULL, ADD channel_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE channel_hotel ADD CONSTRAINT FK_B47256413243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id)');
        $this->addSql('ALTER TABLE channel_hotel ADD CONSTRAINT FK_B472564172F5A1AA FOREIGN KEY (channel_id) REFERENCES channel (id)');
        $this->addSql('CREATE INDEX IDX_B47256413243BB18 ON channel_hotel (hotel_id)');
        $this->addSql('CREATE INDEX IDX_B472564172F5A1AA ON channel_hotel (channel_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE channel_hotel DROP FOREIGN KEY FK_B47256413243BB18');
        $this->addSql('ALTER TABLE channel_hotel DROP FOREIGN KEY FK_B472564172F5A1AA');
        $this->addSql('DROP INDEX IDX_B47256413243BB18 ON channel_hotel');
        $this->addSql('DROP INDEX IDX_B472564172F5A1AA ON channel_hotel');
        $this->addSql('ALTER TABLE channel_hotel DROP hotel_id, DROP channel_id');
    }
}
