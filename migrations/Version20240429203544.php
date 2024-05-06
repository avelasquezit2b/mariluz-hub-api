<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240429203544 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking ADD payment_status VARCHAR(25) DEFAULT NULL');
        $this->addSql('ALTER TABLE room_condition ADD min_nights_supplement INT DEFAULT NULL, ADD nights_supplement VARCHAR(25) DEFAULT NULL');
        $this->addSql('ALTER TABLE room_type ADD min_babies_capacity INT DEFAULT NULL, ADD max_babies_capacity INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking DROP payment_status');
        $this->addSql('ALTER TABLE room_condition DROP min_nights_supplement, DROP nights_supplement');
        $this->addSql('ALTER TABLE room_type DROP min_babies_capacity, DROP max_babies_capacity');
    }
}
