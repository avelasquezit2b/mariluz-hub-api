<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240131232727 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE room_discount (id INT AUTO_INCREMENT NOT NULL, room_condition_id INT DEFAULT NULL, type VARCHAR(25) DEFAULT NULL, age_from INT DEFAULT NULL, age_to INT DEFAULT NULL, discount VARCHAR(25) DEFAULT NULL, number INT DEFAULT NULL, INDEX IDX_410674CA3E058B25 (room_condition_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE room_discount ADD CONSTRAINT FK_410674CA3E058B25 FOREIGN KEY (room_condition_id) REFERENCES room_condition (id)');
        $this->addSql('ALTER TABLE hotel ADD min_adults_capacity INT DEFAULT NULL, ADD max_adults_capacity INT DEFAULT NULL, ADD min_kids_capacity INT DEFAULT NULL, ADD max_kids_capacity INT DEFAULT NULL, ADD total_capacity INT DEFAULT NULL, ADD adults_from INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hotel_condition ADD hotel_season_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hotel_condition ADD CONSTRAINT FK_39FF981F0897013 FOREIGN KEY (hotel_season_id) REFERENCES hotel_season (id)');
        $this->addSql('CREATE INDEX IDX_39FF981F0897013 ON hotel_condition (hotel_season_id)');
        $this->addSql('ALTER TABLE room_condition ADD min_stay INT DEFAULT NULL, ADD individual_supplement VARCHAR(25) DEFAULT NULL, ADD extras LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE room_discount DROP FOREIGN KEY FK_410674CA3E058B25');
        $this->addSql('DROP TABLE room_discount');
        $this->addSql('ALTER TABLE hotel DROP min_adults_capacity, DROP max_adults_capacity, DROP min_kids_capacity, DROP max_kids_capacity, DROP total_capacity, DROP adults_from');
        $this->addSql('ALTER TABLE hotel_condition DROP FOREIGN KEY FK_39FF981F0897013');
        $this->addSql('DROP INDEX IDX_39FF981F0897013 ON hotel_condition');
        $this->addSql('ALTER TABLE hotel_condition DROP hotel_season_id');
        $this->addSql('ALTER TABLE room_condition DROP min_stay, DROP individual_supplement, DROP extras');
    }
}
