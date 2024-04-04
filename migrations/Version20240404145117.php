<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240404145117 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bill ADD booking_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bill ADD CONSTRAINT FK_7A2119E33301C60 FOREIGN KEY (booking_id) REFERENCES booking (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7A2119E33301C60 ON bill (booking_id)');
        $this->addSql('ALTER TABLE voucher ADD booking_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE voucher ADD CONSTRAINT FK_1392A5D83301C60 FOREIGN KEY (booking_id) REFERENCES booking (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1392A5D83301C60 ON voucher (booking_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bill DROP FOREIGN KEY FK_7A2119E33301C60');
        $this->addSql('DROP INDEX UNIQ_7A2119E33301C60 ON bill');
        $this->addSql('ALTER TABLE bill DROP booking_id');
        $this->addSql('ALTER TABLE voucher DROP FOREIGN KEY FK_1392A5D83301C60');
        $this->addSql('DROP INDEX UNIQ_1392A5D83301C60 ON voucher');
        $this->addSql('ALTER TABLE voucher DROP booking_id');
    }
}
