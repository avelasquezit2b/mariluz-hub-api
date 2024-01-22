<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240119112142 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hotel_services ADD hotel_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hotel_services ADD CONSTRAINT FK_3153331B3243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3153331B3243BB18 ON hotel_services (hotel_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hotel_services DROP FOREIGN KEY FK_3153331B3243BB18');
        $this->addSql('DROP INDEX UNIQ_3153331B3243BB18 ON hotel_services');
        $this->addSql('ALTER TABLE hotel_services DROP hotel_id');
    }
}
