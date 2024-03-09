<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307120452 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE itinerary_day (id INT AUTO_INCREMENT NOT NULL, number SMALLINT NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE itinerary_day_hotel (itinerary_day_id INT NOT NULL, hotel_id INT NOT NULL, INDEX IDX_56DCF22F7A725613 (itinerary_day_id), INDEX IDX_56DCF22F3243BB18 (hotel_id), PRIMARY KEY(itinerary_day_id, hotel_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE itinerary_day_activity (itinerary_day_id INT NOT NULL, activity_id INT NOT NULL, INDEX IDX_62886B397A725613 (itinerary_day_id), INDEX IDX_62886B3981C06096 (activity_id), PRIMARY KEY(itinerary_day_id, activity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE itinerary_day_hotel ADD CONSTRAINT FK_56DCF22F7A725613 FOREIGN KEY (itinerary_day_id) REFERENCES itinerary_day (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE itinerary_day_hotel ADD CONSTRAINT FK_56DCF22F3243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE itinerary_day_activity ADD CONSTRAINT FK_62886B397A725613 FOREIGN KEY (itinerary_day_id) REFERENCES itinerary_day (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE itinerary_day_activity ADD CONSTRAINT FK_62886B3981C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE itinerary_day_hotel DROP FOREIGN KEY FK_56DCF22F7A725613');
        $this->addSql('ALTER TABLE itinerary_day_hotel DROP FOREIGN KEY FK_56DCF22F3243BB18');
        $this->addSql('ALTER TABLE itinerary_day_activity DROP FOREIGN KEY FK_62886B397A725613');
        $this->addSql('ALTER TABLE itinerary_day_activity DROP FOREIGN KEY FK_62886B3981C06096');
        $this->addSql('DROP TABLE itinerary_day');
        $this->addSql('DROP TABLE itinerary_day_hotel');
        $this->addSql('DROP TABLE itinerary_day_activity');
    }
}
