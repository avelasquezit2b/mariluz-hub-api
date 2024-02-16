<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240215194104 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hotel_availability_hotel_booking (hotel_availability_id INT NOT NULL, hotel_booking_id INT NOT NULL, INDEX IDX_37C4F6F6D039360B (hotel_availability_id), INDEX IDX_37C4F6F676E4F68D (hotel_booking_id), PRIMARY KEY(hotel_availability_id, hotel_booking_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hotel_availability_hotel_booking ADD CONSTRAINT FK_37C4F6F6D039360B FOREIGN KEY (hotel_availability_id) REFERENCES hotel_availability (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hotel_availability_hotel_booking ADD CONSTRAINT FK_37C4F6F676E4F68D FOREIGN KEY (hotel_booking_id) REFERENCES hotel_booking (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hotel_availability ADD max_quota INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hotel_availability_hotel_booking DROP FOREIGN KEY FK_37C4F6F6D039360B');
        $this->addSql('ALTER TABLE hotel_availability_hotel_booking DROP FOREIGN KEY FK_37C4F6F676E4F68D');
        $this->addSql('DROP TABLE hotel_availability_hotel_booking');
        $this->addSql('ALTER TABLE hotel_availability DROP max_quota');
    }
}
