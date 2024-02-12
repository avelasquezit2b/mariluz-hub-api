<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240207214049 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity_availability_activity_price DROP FOREIGN KEY FK_FDA2B27F2C59EDEA');
        $this->addSql('ALTER TABLE activity_availability_activity_price DROP FOREIGN KEY FK_FDA2B27F761E77E9');
        $this->addSql('DROP TABLE activity_availability_activity_price');
        $this->addSql('ALTER TABLE activity_availability ADD activity_schedule_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE activity_availability ADD CONSTRAINT FK_360F60FEAA85A8D9 FOREIGN KEY (activity_schedule_id) REFERENCES activity_schedule (id)');
        $this->addSql('CREATE INDEX IDX_360F60FEAA85A8D9 ON activity_availability (activity_schedule_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity_availability_activity_price (activity_availability_id INT NOT NULL, activity_price_id INT NOT NULL, INDEX IDX_FDA2B27F2C59EDEA (activity_availability_id), INDEX IDX_FDA2B27F761E77E9 (activity_price_id), PRIMARY KEY(activity_availability_id, activity_price_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE activity_availability_activity_price ADD CONSTRAINT FK_FDA2B27F2C59EDEA FOREIGN KEY (activity_availability_id) REFERENCES activity_availability (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_availability_activity_price ADD CONSTRAINT FK_FDA2B27F761E77E9 FOREIGN KEY (activity_price_id) REFERENCES activity_price (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_availability DROP FOREIGN KEY FK_360F60FEAA85A8D9');
        $this->addSql('DROP INDEX IDX_360F60FEAA85A8D9 ON activity_availability');
        $this->addSql('ALTER TABLE activity_availability DROP activity_schedule_id');
    }
}
