<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240209101932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hotel_availability (id INT AUTO_INCREMENT NOT NULL, room_condition_id INT DEFAULT NULL, date DATE NOT NULL, quota INT NOT NULL, is_available TINYINT(1) NOT NULL, INDEX IDX_94B851AC3E058B25 (room_condition_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hotel_availability ADD CONSTRAINT FK_94B851AC3E058B25 FOREIGN KEY (room_condition_id) REFERENCES room_condition (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hotel_availability DROP FOREIGN KEY FK_94B851AC3E058B25');
        $this->addSql('DROP TABLE hotel_availability');
    }
}
