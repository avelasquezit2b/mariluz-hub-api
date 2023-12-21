<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231214184256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity_price (id INT AUTO_INCREMENT NOT NULL, client_type_id INT DEFAULT NULL, activity_schedule_id INT DEFAULT NULL, price VARCHAR(50) DEFAULT NULL, cost VARCHAR(50) DEFAULT NULL, quota INT DEFAULT NULL, INDEX IDX_D4CA146A9771C8EE (client_type_id), INDEX IDX_D4CA146AAA85A8D9 (activity_schedule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activity_schedule (id INT AUTO_INCREMENT NOT NULL, activity_season_id INT DEFAULT NULL, start_time VARCHAR(25) DEFAULT NULL, end_time VARCHAR(25) DEFAULT NULL, duration VARCHAR(25) DEFAULT NULL, INDEX IDX_FA32A1F5A9D82666 (activity_season_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activity_season (id INT AUTO_INCREMENT NOT NULL, activity_fee_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, week_days LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', applies_to_all_schedules TINYINT(1) NOT NULL, ranges LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_A292BBA9F9334ECA (activity_fee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity_price ADD CONSTRAINT FK_D4CA146A9771C8EE FOREIGN KEY (client_type_id) REFERENCES client_type (id)');
        $this->addSql('ALTER TABLE activity_price ADD CONSTRAINT FK_D4CA146AAA85A8D9 FOREIGN KEY (activity_schedule_id) REFERENCES activity_schedule (id)');
        $this->addSql('ALTER TABLE activity_schedule ADD CONSTRAINT FK_FA32A1F5A9D82666 FOREIGN KEY (activity_season_id) REFERENCES activity_season (id)');
        $this->addSql('ALTER TABLE activity_season ADD CONSTRAINT FK_A292BBA9F9334ECA FOREIGN KEY (activity_fee_id) REFERENCES activity_fee (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity_price DROP FOREIGN KEY FK_D4CA146A9771C8EE');
        $this->addSql('ALTER TABLE activity_price DROP FOREIGN KEY FK_D4CA146AAA85A8D9');
        $this->addSql('ALTER TABLE activity_schedule DROP FOREIGN KEY FK_FA32A1F5A9D82666');
        $this->addSql('ALTER TABLE activity_season DROP FOREIGN KEY FK_A292BBA9F9334ECA');
        $this->addSql('DROP TABLE activity_price');
        $this->addSql('DROP TABLE activity_schedule');
        $this->addSql('DROP TABLE activity_season');
    }
}
