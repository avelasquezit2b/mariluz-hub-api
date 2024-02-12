<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240130162722 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hotel_hotel (hotel_source INT NOT NULL, hotel_target INT NOT NULL, INDEX IDX_E087901DC360AD14 (hotel_source), INDEX IDX_E087901DDA85FD9B (hotel_target), PRIMARY KEY(hotel_source, hotel_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hotel_hotel ADD CONSTRAINT FK_E087901DC360AD14 FOREIGN KEY (hotel_source) REFERENCES hotel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hotel_hotel ADD CONSTRAINT FK_E087901DDA85FD9B FOREIGN KEY (hotel_target) REFERENCES hotel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media_object ADD hotel_id INT DEFAULT NULL, ADD room_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media_object ADD CONSTRAINT FK_14D431323243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id)');
        $this->addSql('ALTER TABLE media_object ADD CONSTRAINT FK_14D43132296E3073 FOREIGN KEY (room_type_id) REFERENCES room_type (id)');
        $this->addSql('CREATE INDEX IDX_14D431323243BB18 ON media_object (hotel_id)');
        $this->addSql('CREATE INDEX IDX_14D43132296E3073 ON media_object (room_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hotel_hotel DROP FOREIGN KEY FK_E087901DC360AD14');
        $this->addSql('ALTER TABLE hotel_hotel DROP FOREIGN KEY FK_E087901DDA85FD9B');
        $this->addSql('DROP TABLE hotel_hotel');
        $this->addSql('ALTER TABLE media_object DROP FOREIGN KEY FK_14D431323243BB18');
        $this->addSql('ALTER TABLE media_object DROP FOREIGN KEY FK_14D43132296E3073');
        $this->addSql('DROP INDEX IDX_14D431323243BB18 ON media_object');
        $this->addSql('DROP INDEX IDX_14D43132296E3073 ON media_object');
        $this->addSql('ALTER TABLE media_object DROP hotel_id, DROP room_type_id');
    }
}
