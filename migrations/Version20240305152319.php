<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240305152319 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE modality_hotel (modality_id INT NOT NULL, hotel_id INT NOT NULL, INDEX IDX_2B495D242D6D889B (modality_id), INDEX IDX_2B495D243243BB18 (hotel_id), PRIMARY KEY(modality_id, hotel_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE modality_activity (modality_id INT NOT NULL, activity_id INT NOT NULL, INDEX IDX_C72743482D6D889B (modality_id), INDEX IDX_C727434881C06096 (activity_id), PRIMARY KEY(modality_id, activity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE modality_hotel ADD CONSTRAINT FK_2B495D242D6D889B FOREIGN KEY (modality_id) REFERENCES modality (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE modality_hotel ADD CONSTRAINT FK_2B495D243243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE modality_activity ADD CONSTRAINT FK_C72743482D6D889B FOREIGN KEY (modality_id) REFERENCES modality (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE modality_activity ADD CONSTRAINT FK_C727434881C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pack_activity DROP FOREIGN KEY FK_82FB4D4B1919B217');
        $this->addSql('ALTER TABLE pack_activity DROP FOREIGN KEY FK_82FB4D4B81C06096');
        $this->addSql('ALTER TABLE pack_hotel DROP FOREIGN KEY FK_B1A7D4A81919B217');
        $this->addSql('ALTER TABLE pack_hotel DROP FOREIGN KEY FK_B1A7D4A83243BB18');
        $this->addSql('DROP TABLE pack_activity');
        $this->addSql('DROP TABLE pack_hotel');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pack_activity (pack_id INT NOT NULL, activity_id INT NOT NULL, INDEX IDX_82FB4D4B1919B217 (pack_id), INDEX IDX_82FB4D4B81C06096 (activity_id), PRIMARY KEY(pack_id, activity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE pack_hotel (pack_id INT NOT NULL, hotel_id INT NOT NULL, INDEX IDX_B1A7D4A81919B217 (pack_id), INDEX IDX_B1A7D4A83243BB18 (hotel_id), PRIMARY KEY(pack_id, hotel_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE pack_activity ADD CONSTRAINT FK_82FB4D4B1919B217 FOREIGN KEY (pack_id) REFERENCES pack (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pack_activity ADD CONSTRAINT FK_82FB4D4B81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pack_hotel ADD CONSTRAINT FK_B1A7D4A81919B217 FOREIGN KEY (pack_id) REFERENCES pack (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pack_hotel ADD CONSTRAINT FK_B1A7D4A83243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE modality_hotel DROP FOREIGN KEY FK_2B495D242D6D889B');
        $this->addSql('ALTER TABLE modality_hotel DROP FOREIGN KEY FK_2B495D243243BB18');
        $this->addSql('ALTER TABLE modality_activity DROP FOREIGN KEY FK_C72743482D6D889B');
        $this->addSql('ALTER TABLE modality_activity DROP FOREIGN KEY FK_C727434881C06096');
        $this->addSql('DROP TABLE modality_hotel');
        $this->addSql('DROP TABLE modality_activity');
    }
}
