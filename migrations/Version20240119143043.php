<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240119143043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hotel ADD services_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hotel ADD CONSTRAINT FK_3535ED9AEF5A6C1 FOREIGN KEY (services_id) REFERENCES hotel_services (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3535ED9AEF5A6C1 ON hotel (services_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hotel DROP FOREIGN KEY FK_3535ED9AEF5A6C1');
        $this->addSql('DROP INDEX UNIQ_3535ED9AEF5A6C1 ON hotel');
        $this->addSql('ALTER TABLE hotel DROP services_id');
    }
}
