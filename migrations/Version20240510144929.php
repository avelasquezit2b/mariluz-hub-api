<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240510144929 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity ADD internal_notes LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE booking ADD internal_notes LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE hotel ADD internal_notes LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity DROP internal_notes');
        $this->addSql('ALTER TABLE booking DROP internal_notes');
        $this->addSql('ALTER TABLE hotel DROP internal_notes');
    }
}
