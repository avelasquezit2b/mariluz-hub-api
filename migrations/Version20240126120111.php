<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240126120111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pickup_schedule (id INT AUTO_INCREMENT NOT NULL, pickup_id INT DEFAULT NULL, start_time VARCHAR(25) NOT NULL, INDEX IDX_F230ECA8C26E160B (pickup_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pickup_schedule ADD CONSTRAINT FK_F230ECA8C26E160B FOREIGN KEY (pickup_id) REFERENCES pickup (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pickup_schedule DROP FOREIGN KEY FK_F230ECA8C26E160B');
        $this->addSql('DROP TABLE pickup_schedule');
    }
}
