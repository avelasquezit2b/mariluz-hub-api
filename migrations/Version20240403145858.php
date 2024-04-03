<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240403145858 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE voucher (id INT AUTO_INCREMENT NOT NULL, hotel_id INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', to_be_paid_by VARCHAR(255) DEFAULT NULL, INDEX IDX_1392A5D83243BB18 (hotel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE voucher ADD CONSTRAINT FK_1392A5D83243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id)');
        $this->addSql('ALTER TABLE hotel_booking ADD voucher_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hotel_booking ADD CONSTRAINT FK_3346F3F728AA1B6F FOREIGN KEY (voucher_id) REFERENCES voucher (id)');
        $this->addSql('CREATE INDEX IDX_3346F3F728AA1B6F ON hotel_booking (voucher_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hotel_booking DROP FOREIGN KEY FK_3346F3F728AA1B6F');
        $this->addSql('ALTER TABLE voucher DROP FOREIGN KEY FK_1392A5D83243BB18');
        $this->addSql('DROP TABLE voucher');
        $this->addSql('DROP INDEX IDX_3346F3F728AA1B6F ON hotel_booking');
        $this->addSql('ALTER TABLE hotel_booking DROP voucher_id');
    }
}
