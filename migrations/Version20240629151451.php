<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240629151451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity ADD release_days INT DEFAULT NULL, ADD days_to_pay INT DEFAULT NULL, ADD days_to_pay_before_stay INT DEFAULT NULL, ADD is_credit TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE activity_season ADD is_on_request TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE hotel_season ADD is_on_request TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity DROP release_days, DROP days_to_pay, DROP days_to_pay_before_stay, DROP is_credit');
        $this->addSql('ALTER TABLE activity_season DROP is_on_request');
        $this->addSql('ALTER TABLE hotel_season DROP is_on_request');
    }
}
