<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240127213633 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cancellation_type (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, code VARCHAR(25) DEFAULT NULL, description LONGTEXT DEFAULT NULL, custom_title VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotel_condition (id INT AUTO_INCREMENT NOT NULL, cancellation_type_id INT DEFAULT NULL, INDEX IDX_39FF981B0AFEE5E (cancellation_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotel_fee (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotel_fee_occupancy_type (hotel_fee_id INT NOT NULL, occupancy_type_id INT NOT NULL, INDEX IDX_D7253AE537AF7CAD (hotel_fee_id), INDEX IDX_D7253AE5E8D9C706 (occupancy_type_id), PRIMARY KEY(hotel_fee_id, occupancy_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotel_fee_cancellation_type (hotel_fee_id INT NOT NULL, cancellation_type_id INT NOT NULL, INDEX IDX_E0984E437AF7CAD (hotel_fee_id), INDEX IDX_E0984E4B0AFEE5E (cancellation_type_id), PRIMARY KEY(hotel_fee_id, cancellation_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotel_season (id INT AUTO_INCREMENT NOT NULL, hotel_fee_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, ranges LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_6C0E89CE37AF7CAD (hotel_fee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pension_type_price (id INT AUTO_INCREMENT NOT NULL, pension_type_id INT DEFAULT NULL, room_condition_id INT DEFAULT NULL, price VARCHAR(50) DEFAULT NULL, cost VARCHAR(50) DEFAULT NULL, INDEX IDX_21B90FD035C2A214 (pension_type_id), INDEX IDX_21B90FD03E058B25 (room_condition_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room_condition (id INT AUTO_INCREMENT NOT NULL, room_type_id INT DEFAULT NULL, hotel_condition_id INT DEFAULT NULL, quota INT DEFAULT NULL, INDEX IDX_217F682A296E3073 (room_type_id), INDEX IDX_217F682A237860A5 (hotel_condition_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hotel_condition ADD CONSTRAINT FK_39FF981B0AFEE5E FOREIGN KEY (cancellation_type_id) REFERENCES cancellation_type (id)');
        $this->addSql('ALTER TABLE hotel_fee_occupancy_type ADD CONSTRAINT FK_D7253AE537AF7CAD FOREIGN KEY (hotel_fee_id) REFERENCES hotel_fee (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hotel_fee_occupancy_type ADD CONSTRAINT FK_D7253AE5E8D9C706 FOREIGN KEY (occupancy_type_id) REFERENCES occupancy_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hotel_fee_cancellation_type ADD CONSTRAINT FK_E0984E437AF7CAD FOREIGN KEY (hotel_fee_id) REFERENCES hotel_fee (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hotel_fee_cancellation_type ADD CONSTRAINT FK_E0984E4B0AFEE5E FOREIGN KEY (cancellation_type_id) REFERENCES cancellation_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hotel_season ADD CONSTRAINT FK_6C0E89CE37AF7CAD FOREIGN KEY (hotel_fee_id) REFERENCES hotel_fee (id)');
        $this->addSql('ALTER TABLE pension_type_price ADD CONSTRAINT FK_21B90FD035C2A214 FOREIGN KEY (pension_type_id) REFERENCES pension_type (id)');
        $this->addSql('ALTER TABLE pension_type_price ADD CONSTRAINT FK_21B90FD03E058B25 FOREIGN KEY (room_condition_id) REFERENCES room_condition (id)');
        $this->addSql('ALTER TABLE room_condition ADD CONSTRAINT FK_217F682A296E3073 FOREIGN KEY (room_type_id) REFERENCES room_type (id)');
        $this->addSql('ALTER TABLE room_condition ADD CONSTRAINT FK_217F682A237860A5 FOREIGN KEY (hotel_condition_id) REFERENCES hotel_condition (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hotel_condition DROP FOREIGN KEY FK_39FF981B0AFEE5E');
        $this->addSql('ALTER TABLE hotel_fee_occupancy_type DROP FOREIGN KEY FK_D7253AE537AF7CAD');
        $this->addSql('ALTER TABLE hotel_fee_occupancy_type DROP FOREIGN KEY FK_D7253AE5E8D9C706');
        $this->addSql('ALTER TABLE hotel_fee_cancellation_type DROP FOREIGN KEY FK_E0984E437AF7CAD');
        $this->addSql('ALTER TABLE hotel_fee_cancellation_type DROP FOREIGN KEY FK_E0984E4B0AFEE5E');
        $this->addSql('ALTER TABLE hotel_season DROP FOREIGN KEY FK_6C0E89CE37AF7CAD');
        $this->addSql('ALTER TABLE pension_type_price DROP FOREIGN KEY FK_21B90FD035C2A214');
        $this->addSql('ALTER TABLE pension_type_price DROP FOREIGN KEY FK_21B90FD03E058B25');
        $this->addSql('ALTER TABLE room_condition DROP FOREIGN KEY FK_217F682A296E3073');
        $this->addSql('ALTER TABLE room_condition DROP FOREIGN KEY FK_217F682A237860A5');
        $this->addSql('DROP TABLE cancellation_type');
        $this->addSql('DROP TABLE hotel_condition');
        $this->addSql('DROP TABLE hotel_fee');
        $this->addSql('DROP TABLE hotel_fee_occupancy_type');
        $this->addSql('DROP TABLE hotel_fee_cancellation_type');
        $this->addSql('DROP TABLE hotel_season');
        $this->addSql('DROP TABLE pension_type_price');
        $this->addSql('DROP TABLE room_condition');
    }
}
