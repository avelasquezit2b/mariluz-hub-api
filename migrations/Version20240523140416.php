<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240523140416 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE about_us_module (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(50) DEFAULT NULL, first_icon VARCHAR(255) DEFAULT NULL, first_subtitle VARCHAR(255) DEFAULT NULL, first_text VARCHAR(255) DEFAULT NULL, second_icon VARCHAR(255) DEFAULT NULL, second_subtitle VARCHAR(255) DEFAULT NULL, second_text VARCHAR(255) DEFAULT NULL, third_icon VARCHAR(255) DEFAULT NULL, third_subtitle VARCHAR(255) DEFAULT NULL, third_text VARCHAR(255) DEFAULT NULL, fourth_icon VARCHAR(255) DEFAULT NULL, fourth_subtitle VARCHAR(255) DEFAULT NULL, fourth_text VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE about_us_module');
    }
}
