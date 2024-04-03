<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240403144723 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bill ADD client_id INT DEFAULT NULL, ADD price_payed VARCHAR(255) DEFAULT NULL, ADD aditional_description LONGTEXT DEFAULT NULL, ADD accounting_code VARCHAR(255) DEFAULT NULL, DROP total_amount, DROP total_price_without_taxes');
        $this->addSql('ALTER TABLE bill ADD CONSTRAINT FK_7A2119E319EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_7A2119E319EB6921 ON bill (client_id)');
        $this->addSql('ALTER TABLE hotel_booking ADD bill_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hotel_booking ADD CONSTRAINT FK_3346F3F71A8C12F5 FOREIGN KEY (bill_id) REFERENCES bill (id)');
        $this->addSql('CREATE INDEX IDX_3346F3F71A8C12F5 ON hotel_booking (bill_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bill DROP FOREIGN KEY FK_7A2119E319EB6921');
        $this->addSql('DROP INDEX IDX_7A2119E319EB6921 ON bill');
        $this->addSql('ALTER TABLE bill ADD total_amount VARCHAR(255) DEFAULT NULL, ADD total_price_without_taxes VARCHAR(255) DEFAULT NULL, DROP client_id, DROP price_payed, DROP aditional_description, DROP accounting_code');
        $this->addSql('ALTER TABLE hotel_booking DROP FOREIGN KEY FK_3346F3F71A8C12F5');
        $this->addSql('DROP INDEX IDX_3346F3F71A8C12F5 ON hotel_booking');
        $this->addSql('ALTER TABLE hotel_booking DROP bill_id');
    }
}
