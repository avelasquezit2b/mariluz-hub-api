<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240329233006 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity_booking (id INT AUTO_INCREMENT NOT NULL, activity_id INT DEFAULT NULL, client_id INT DEFAULT NULL, check_in DATE NOT NULL, check_out DATE NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(25) DEFAULT NULL, observations VARCHAR(255) DEFAULT NULL, promo_code VARCHAR(25) DEFAULT NULL, total_price VARCHAR(25) NOT NULL, payment_method VARCHAR(25) NOT NULL, data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', has_acceptance TINYINT(1) DEFAULT NULL, status VARCHAR(25) NOT NULL, INDEX IDX_E05E9B3E81C06096 (activity_id), INDEX IDX_E05E9B3E19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activity_booking_activity_availability (activity_booking_id INT NOT NULL, activity_availability_id INT NOT NULL, INDEX IDX_903BC7B356D22268 (activity_booking_id), INDEX IDX_903BC7B32C59EDEA (activity_availability_id), PRIMARY KEY(activity_booking_id, activity_availability_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity_booking ADD CONSTRAINT FK_E05E9B3E81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE activity_booking ADD CONSTRAINT FK_E05E9B3E19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE activity_booking_activity_availability ADD CONSTRAINT FK_903BC7B356D22268 FOREIGN KEY (activity_booking_id) REFERENCES activity_booking (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_booking_activity_availability ADD CONSTRAINT FK_903BC7B32C59EDEA FOREIGN KEY (activity_availability_id) REFERENCES activity_availability (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity_booking DROP FOREIGN KEY FK_E05E9B3E81C06096');
        $this->addSql('ALTER TABLE activity_booking DROP FOREIGN KEY FK_E05E9B3E19EB6921');
        $this->addSql('ALTER TABLE activity_booking_activity_availability DROP FOREIGN KEY FK_903BC7B356D22268');
        $this->addSql('ALTER TABLE activity_booking_activity_availability DROP FOREIGN KEY FK_903BC7B32C59EDEA');
        $this->addSql('DROP TABLE activity_booking');
        $this->addSql('DROP TABLE activity_booking_activity_availability');
    }
}
