<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240127145211 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE occupancy_type (id INT AUTO_INCREMENT NOT NULL, total_capacity INT NOT NULL, min_adult_capacity INT NOT NULL, max_adult_capacity INT NOT NULL, min_kids_capacity INT NOT NULL, max_kids_capacity INT NOT NULL, code VARCHAR(25) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pension_type ADD custom_title VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE room_type ADD occupancy_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE room_type ADD CONSTRAINT FK_EFDABD4DE8D9C706 FOREIGN KEY (occupancy_type_id) REFERENCES occupancy_type (id)');
        $this->addSql('CREATE INDEX IDX_EFDABD4DE8D9C706 ON room_type (occupancy_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE room_type DROP FOREIGN KEY FK_EFDABD4DE8D9C706');
        $this->addSql('DROP TABLE occupancy_type');
        $this->addSql('ALTER TABLE pension_type DROP custom_title');
        $this->addSql('DROP INDEX IDX_EFDABD4DE8D9C706 ON room_type');
        $this->addSql('ALTER TABLE room_type DROP occupancy_type_id');
    }
}
