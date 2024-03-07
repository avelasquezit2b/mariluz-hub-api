<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307123151 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE itinerary_day ADD modality_id INT NOT NULL');
        $this->addSql('ALTER TABLE itinerary_day ADD CONSTRAINT FK_AB0E64942D6D889B FOREIGN KEY (modality_id) REFERENCES modality (id)');
        $this->addSql('CREATE INDEX IDX_AB0E64942D6D889B ON itinerary_day (modality_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE itinerary_day DROP FOREIGN KEY FK_AB0E64942D6D889B');
        $this->addSql('DROP INDEX IDX_AB0E64942D6D889B ON itinerary_day');
        $this->addSql('ALTER TABLE itinerary_day DROP modality_id');
    }
}
