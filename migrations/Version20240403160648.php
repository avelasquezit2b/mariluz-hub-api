<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240403160648 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, check_in DATE DEFAULT NULL, check_out DATE DEFAULT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(25) DEFAULT NULL, observations VARCHAR(255) DEFAULT NULL, promo_code VARCHAR(25) DEFAULT NULL, total_price VARCHAR(25) NOT NULL, payment_method VARCHAR(25) NOT NULL, data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', has_acceptance TINYINT(1) NOT NULL, status VARCHAR(25) NOT NULL, INDEX IDX_E00CEDDE19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE activity_booking DROP FOREIGN KEY FK_E05E9B3E19EB6921');
        $this->addSql('ALTER TABLE activity_booking DROP FOREIGN KEY FK_E05E9B3E81C06096');
        $this->addSql('ALTER TABLE activity_booking_activity_availability DROP FOREIGN KEY FK_903BC7B32C59EDEA');
        $this->addSql('ALTER TABLE activity_booking_activity_availability DROP FOREIGN KEY FK_903BC7B356D22268');
        $this->addSql('ALTER TABLE hotel_availability_hotel_booking DROP FOREIGN KEY FK_37C4F6F676E4F68D');
        $this->addSql('ALTER TABLE hotel_availability_hotel_booking DROP FOREIGN KEY FK_37C4F6F6D039360B');
        $this->addSql('ALTER TABLE hotel_booking DROP FOREIGN KEY FK_3346F3F719EB6921');
        $this->addSql('ALTER TABLE hotel_booking DROP FOREIGN KEY FK_3346F3F71A8C12F5');
        $this->addSql('ALTER TABLE hotel_booking DROP FOREIGN KEY FK_3346F3F728AA1B6F');
        $this->addSql('ALTER TABLE hotel_booking DROP FOREIGN KEY FK_3346F3F73243BB18');
        $this->addSql('DROP TABLE activity_booking');
        $this->addSql('DROP TABLE activity_booking_activity_availability');
        $this->addSql('DROP TABLE hotel_availability_hotel_booking');
        $this->addSql('DROP TABLE hotel_booking');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity_booking (id INT AUTO_INCREMENT NOT NULL, activity_id INT DEFAULT NULL, client_id INT DEFAULT NULL, check_in DATE NOT NULL, check_out DATE NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, phone VARCHAR(25) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, observations VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, promo_code VARCHAR(25) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, total_price VARCHAR(25) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, payment_method VARCHAR(25) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, data LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', has_acceptance TINYINT(1) DEFAULT NULL, status VARCHAR(25) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_E05E9B3E81C06096 (activity_id), INDEX IDX_E05E9B3E19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE activity_booking_activity_availability (activity_booking_id INT NOT NULL, activity_availability_id INT NOT NULL, INDEX IDX_903BC7B356D22268 (activity_booking_id), INDEX IDX_903BC7B32C59EDEA (activity_availability_id), PRIMARY KEY(activity_booking_id, activity_availability_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE hotel_availability_hotel_booking (hotel_availability_id INT NOT NULL, hotel_booking_id INT NOT NULL, INDEX IDX_37C4F6F6D039360B (hotel_availability_id), INDEX IDX_37C4F6F676E4F68D (hotel_booking_id), PRIMARY KEY(hotel_availability_id, hotel_booking_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE hotel_booking (id INT AUTO_INCREMENT NOT NULL, hotel_id INT DEFAULT NULL, client_id INT DEFAULT NULL, bill_id INT DEFAULT NULL, voucher_id INT DEFAULT NULL, check_in DATE NOT NULL, check_out DATE NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, phone VARCHAR(25) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, observations VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, promo_code VARCHAR(25) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, total_price VARCHAR(25) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, payment_method VARCHAR(25) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, rooms LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', has_acceptance TINYINT(1) DEFAULT NULL, status VARCHAR(25) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_3346F3F73243BB18 (hotel_id), INDEX IDX_3346F3F719EB6921 (client_id), INDEX IDX_3346F3F71A8C12F5 (bill_id), INDEX IDX_3346F3F728AA1B6F (voucher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE activity_booking ADD CONSTRAINT FK_E05E9B3E19EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE activity_booking ADD CONSTRAINT FK_E05E9B3E81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE activity_booking_activity_availability ADD CONSTRAINT FK_903BC7B32C59EDEA FOREIGN KEY (activity_availability_id) REFERENCES activity_availability (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_booking_activity_availability ADD CONSTRAINT FK_903BC7B356D22268 FOREIGN KEY (activity_booking_id) REFERENCES activity_booking (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hotel_availability_hotel_booking ADD CONSTRAINT FK_37C4F6F676E4F68D FOREIGN KEY (hotel_booking_id) REFERENCES hotel_booking (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hotel_availability_hotel_booking ADD CONSTRAINT FK_37C4F6F6D039360B FOREIGN KEY (hotel_availability_id) REFERENCES hotel_availability (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hotel_booking ADD CONSTRAINT FK_3346F3F719EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE hotel_booking ADD CONSTRAINT FK_3346F3F71A8C12F5 FOREIGN KEY (bill_id) REFERENCES bill (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE hotel_booking ADD CONSTRAINT FK_3346F3F728AA1B6F FOREIGN KEY (voucher_id) REFERENCES voucher (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE hotel_booking ADD CONSTRAINT FK_3346F3F73243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE19EB6921');
        $this->addSql('DROP TABLE booking');
    }
}
