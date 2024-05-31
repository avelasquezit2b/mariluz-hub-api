<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240530101015 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE theme_image_description (id INT AUTO_INCREMENT NOT NULL, media_id INT DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_A4E6FD08EA9FDD75 (media_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE theme_image_description ADD CONSTRAINT FK_A4E6FD08EA9FDD75 FOREIGN KEY (media_id) REFERENCES media_object (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE theme_image_description DROP FOREIGN KEY FK_A4E6FD08EA9FDD75');
        $this->addSql('DROP TABLE theme_image_description');
    }
}
