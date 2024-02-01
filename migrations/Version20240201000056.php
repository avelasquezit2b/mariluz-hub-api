<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240201000056 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hotel_fee ADD hotel_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hotel_fee ADD CONSTRAINT FK_EEC3D7CB3243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id)');
        $this->addSql('CREATE INDEX IDX_EEC3D7CB3243BB18 ON hotel_fee (hotel_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hotel_fee DROP FOREIGN KEY FK_EEC3D7CB3243BB18');
        $this->addSql('DROP INDEX IDX_EEC3D7CB3243BB18 ON hotel_fee');
        $this->addSql('ALTER TABLE hotel_fee DROP hotel_id');
    }
}
