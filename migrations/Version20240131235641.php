<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240131235641 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hotel_fee_pension_type (hotel_fee_id INT NOT NULL, pension_type_id INT NOT NULL, INDEX IDX_76504DCD37AF7CAD (hotel_fee_id), INDEX IDX_76504DCD35C2A214 (pension_type_id), PRIMARY KEY(hotel_fee_id, pension_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hotel_fee_pension_type ADD CONSTRAINT FK_76504DCD37AF7CAD FOREIGN KEY (hotel_fee_id) REFERENCES hotel_fee (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hotel_fee_pension_type ADD CONSTRAINT FK_76504DCD35C2A214 FOREIGN KEY (pension_type_id) REFERENCES pension_type (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hotel_fee_pension_type DROP FOREIGN KEY FK_76504DCD37AF7CAD');
        $this->addSql('ALTER TABLE hotel_fee_pension_type DROP FOREIGN KEY FK_76504DCD35C2A214');
        $this->addSql('DROP TABLE hotel_fee_pension_type');
    }
}
