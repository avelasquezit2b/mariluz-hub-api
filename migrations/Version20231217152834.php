<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231217152834 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity_zone (activity_id INT NOT NULL, zone_id INT NOT NULL, INDEX IDX_A32F1B9581C06096 (activity_id), INDEX IDX_A32F1B959F2C3FAB (zone_id), PRIMARY KEY(activity_id, zone_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, latitude VARCHAR(50) DEFAULT NULL, longitude VARCHAR(50) DEFAULT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media_object (id INT AUTO_INCREMENT NOT NULL, activity_id INT DEFAULT NULL, file_path VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, alt VARCHAR(255) DEFAULT NULL, type VARCHAR(25) NOT NULL, INDEX IDX_14D4313281C06096 (activity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE zone (id INT AUTO_INCREMENT NOT NULL, location_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, latitude VARCHAR(50) DEFAULT NULL, longitude VARCHAR(50) DEFAULT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_A0EBC00764D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity_zone ADD CONSTRAINT FK_A32F1B9581C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_zone ADD CONSTRAINT FK_A32F1B959F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media_object ADD CONSTRAINT FK_14D4313281C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE zone ADD CONSTRAINT FK_A0EBC00764D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE activity ADD location_id INT DEFAULT NULL, ADD release_hour VARCHAR(25) DEFAULT NULL, ADD has_supplier_availability TINYINT(1) DEFAULT NULL, ADD has_transfer_availability TINYINT(1) DEFAULT NULL, ADD is_under_petition TINYINT(1) DEFAULT NULL, ADD has_send_email_client TINYINT(1) DEFAULT NULL, ADD has_send_email_agency TINYINT(1) DEFAULT NULL, ADD has_send_email_supplier TINYINT(1) DEFAULT NULL, ADD tripadvisor_id VARCHAR(50) DEFAULT NULL, ADD getyourguide_id VARCHAR(50) DEFAULT NULL, ADD venntur_id VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A64D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('CREATE INDEX IDX_AC74095A64D218E ON activity (location_id)');
        $this->addSql('ALTER TABLE activity_schedule ADD week_days LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A64D218E');
        $this->addSql('ALTER TABLE activity_zone DROP FOREIGN KEY FK_A32F1B9581C06096');
        $this->addSql('ALTER TABLE activity_zone DROP FOREIGN KEY FK_A32F1B959F2C3FAB');
        $this->addSql('ALTER TABLE media_object DROP FOREIGN KEY FK_14D4313281C06096');
        $this->addSql('ALTER TABLE zone DROP FOREIGN KEY FK_A0EBC00764D218E');
        $this->addSql('DROP TABLE activity_zone');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE media_object');
        $this->addSql('DROP TABLE zone');
        $this->addSql('DROP INDEX IDX_AC74095A64D218E ON activity');
        $this->addSql('ALTER TABLE activity DROP location_id, DROP release_hour, DROP has_supplier_availability, DROP has_transfer_availability, DROP is_under_petition, DROP has_send_email_client, DROP has_send_email_agency, DROP has_send_email_supplier, DROP tripadvisor_id, DROP getyourguide_id, DROP venntur_id');
        $this->addSql('ALTER TABLE activity_schedule DROP week_days');
    }
}
