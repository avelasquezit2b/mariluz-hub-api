<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240126212015 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE modality_pickup_schedule (modality_id INT NOT NULL, pickup_schedule_id INT NOT NULL, INDEX IDX_3F268D5C2D6D889B (modality_id), INDEX IDX_3F268D5C65384556 (pickup_schedule_id), PRIMARY KEY(modality_id, pickup_schedule_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE modality_pickup_schedule ADD CONSTRAINT FK_3F268D5C2D6D889B FOREIGN KEY (modality_id) REFERENCES modality (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE modality_pickup_schedule ADD CONSTRAINT FK_3F268D5C65384556 FOREIGN KEY (pickup_schedule_id) REFERENCES pickup_schedule (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE modality_pickup_schedule DROP FOREIGN KEY FK_3F268D5C2D6D889B');
        $this->addSql('ALTER TABLE modality_pickup_schedule DROP FOREIGN KEY FK_3F268D5C65384556');
        $this->addSql('DROP TABLE modality_pickup_schedule');
    }
}
