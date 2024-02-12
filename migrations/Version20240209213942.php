<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240209213942 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hotel_booking (id INT AUTO_INCREMENT NOT NULL, hotel_id INT DEFAULT NULL, check_in DATE NOT NULL, check_out DATE NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(25) DEFAULT NULL, observations VARCHAR(255) DEFAULT NULL, promo_code VARCHAR(25) DEFAULT NULL, total_price VARCHAR(25) NOT NULL, payment_method VARCHAR(25) NOT NULL, rooms LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', has_acceptance TINYINT(1) DEFAULT NULL, status VARCHAR(25) NOT NULL, INDEX IDX_3346F3F73243BB18 (hotel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hotel_booking ADD CONSTRAINT FK_3346F3F73243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hotel_booking DROP FOREIGN KEY FK_3346F3F73243BB18');
        $this->addSql('DROP TABLE hotel_booking');
    }
}
