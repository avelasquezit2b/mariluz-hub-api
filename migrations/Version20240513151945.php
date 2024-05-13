<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240513151945 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking ADD client_confirmation_sent TINYINT(1) DEFAULT NULL, ADD supplier_confirmation_sent TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE room_condition ADD on_request TINYINT(1) DEFAULT NULL, ADD night_supplement_cost VARCHAR(25) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking DROP client_confirmation_sent, DROP supplier_confirmation_sent');
        $this->addSql('ALTER TABLE room_condition DROP on_request, DROP night_supplement_cost');
    }
}
