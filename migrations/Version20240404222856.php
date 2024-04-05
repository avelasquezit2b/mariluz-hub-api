<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240404222856 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE booking_line (id INT AUTO_INCREMENT NOT NULL, hotel_id INT DEFAULT NULL, activity_id INT DEFAULT NULL, booking_id INT DEFAULT NULL, check_in DATE DEFAULT NULL, check_out DATE DEFAULT NULL, total_price VARCHAR(25) NOT NULL, data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_C98596B83243BB18 (hotel_id), INDEX IDX_C98596B881C06096 (activity_id), INDEX IDX_C98596B83301C60 (booking_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE booking_line ADD CONSTRAINT FK_C98596B83243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id)');
        $this->addSql('ALTER TABLE booking_line ADD CONSTRAINT FK_C98596B881C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE booking_line ADD CONSTRAINT FK_C98596B83301C60 FOREIGN KEY (booking_id) REFERENCES booking (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking_line DROP FOREIGN KEY FK_C98596B83243BB18');
        $this->addSql('ALTER TABLE booking_line DROP FOREIGN KEY FK_C98596B881C06096');
        $this->addSql('ALTER TABLE booking_line DROP FOREIGN KEY FK_C98596B83301C60');
        $this->addSql('DROP TABLE booking_line');
    }
}
