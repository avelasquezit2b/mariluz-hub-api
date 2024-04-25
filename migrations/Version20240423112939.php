<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240423112939 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity CHANGE is_under_petition is_on_request TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE hotel ADD has_supplier_availability TINYINT(1) DEFAULT NULL, ADD has_transfer_availability TINYINT(1) DEFAULT NULL, ADD is_on_request TINYINT(1) DEFAULT NULL, ADD has_send_email_client TINYINT(1) DEFAULT NULL, ADD has_send_email_agency TINYINT(1) DEFAULT NULL, ADD has_send_email_supplier TINYINT(1) DEFAULT NULL, ADD days_to_pay INT DEFAULT NULL, ADD days_to_pay_before_stay INT DEFAULT NULL, ADD is_credit TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE pension_type_price ADD commission VARCHAR(25) DEFAULT NULL');
        $this->addSql('ALTER TABLE room_discount ADD is_adult TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity CHANGE is_on_request is_under_petition TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE hotel DROP has_supplier_availability, DROP has_transfer_availability, DROP is_on_request, DROP has_send_email_client, DROP has_send_email_agency, DROP has_send_email_supplier, DROP days_to_pay, DROP days_to_pay_before_stay, DROP is_credit');
        $this->addSql('ALTER TABLE pension_type_price DROP commission');
        $this->addSql('ALTER TABLE room_discount DROP is_adult');
    }
}
