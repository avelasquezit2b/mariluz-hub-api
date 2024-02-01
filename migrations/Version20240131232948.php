<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240131232948 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hotel DROP min_adults_capacity, DROP max_adults_capacity, DROP min_kids_capacity, DROP max_kids_capacity, DROP total_capacity');
        $this->addSql('ALTER TABLE modality ADD min_adults_capacity INT DEFAULT NULL, ADD max_adults_capacity INT DEFAULT NULL, ADD min_kids_capacity INT DEFAULT NULL, ADD max_kids_capacity INT DEFAULT NULL, ADD total_capacity INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hotel ADD min_adults_capacity INT DEFAULT NULL, ADD max_adults_capacity INT DEFAULT NULL, ADD min_kids_capacity INT DEFAULT NULL, ADD max_kids_capacity INT DEFAULT NULL, ADD total_capacity INT DEFAULT NULL');
        $this->addSql('ALTER TABLE modality DROP min_adults_capacity, DROP max_adults_capacity, DROP min_kids_capacity, DROP max_kids_capacity, DROP total_capacity');
    }
}
