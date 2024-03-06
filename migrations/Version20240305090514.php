<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240305090514 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pack_hotel (pack_id INT NOT NULL, hotel_id INT NOT NULL, INDEX IDX_B1A7D4A81919B217 (pack_id), INDEX IDX_B1A7D4A83243BB18 (hotel_id), PRIMARY KEY(pack_id, hotel_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pack_activity (pack_id INT NOT NULL, activity_id INT NOT NULL, INDEX IDX_82FB4D4B1919B217 (pack_id), INDEX IDX_82FB4D4B81C06096 (activity_id), PRIMARY KEY(pack_id, activity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pack_hotel ADD CONSTRAINT FK_B1A7D4A81919B217 FOREIGN KEY (pack_id) REFERENCES pack (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pack_hotel ADD CONSTRAINT FK_B1A7D4A83243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pack_activity ADD CONSTRAINT FK_82FB4D4B1919B217 FOREIGN KEY (pack_id) REFERENCES pack (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pack_activity ADD CONSTRAINT FK_82FB4D4B81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pack_hotel DROP FOREIGN KEY FK_B1A7D4A81919B217');
        $this->addSql('ALTER TABLE pack_hotel DROP FOREIGN KEY FK_B1A7D4A83243BB18');
        $this->addSql('ALTER TABLE pack_activity DROP FOREIGN KEY FK_82FB4D4B1919B217');
        $this->addSql('ALTER TABLE pack_activity DROP FOREIGN KEY FK_82FB4D4B81C06096');
        $this->addSql('DROP TABLE pack_hotel');
        $this->addSql('DROP TABLE pack_activity');
    }
}
