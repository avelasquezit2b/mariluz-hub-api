<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240309213232 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE extra (id INT AUTO_INCREMENT NOT NULL, supplier_id INT DEFAULT NULL, location_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) DEFAULT NULL, short_description VARCHAR(255) DEFAULT NULL, extended_description LONGTEXT DEFAULT NULL, is_active TINYINT(1) NOT NULL, price VARCHAR(25) DEFAULT NULL, INDEX IDX_4D3F0D652ADD6D8C (supplier_id), INDEX IDX_4D3F0D6564D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE extra_zone (extra_id INT NOT NULL, zone_id INT NOT NULL, INDEX IDX_349603192B959FC6 (extra_id), INDEX IDX_349603199F2C3FAB (zone_id), PRIMARY KEY(extra_id, zone_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE extra ADD CONSTRAINT FK_4D3F0D652ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE extra ADD CONSTRAINT FK_4D3F0D6564D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE extra_zone ADD CONSTRAINT FK_349603192B959FC6 FOREIGN KEY (extra_id) REFERENCES extra (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE extra_zone ADD CONSTRAINT FK_349603199F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media_object ADD extra_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media_object ADD CONSTRAINT FK_14D431322B959FC6 FOREIGN KEY (extra_id) REFERENCES extra (id)');
        $this->addSql('CREATE INDEX IDX_14D431322B959FC6 ON media_object (extra_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_object DROP FOREIGN KEY FK_14D431322B959FC6');
        $this->addSql('ALTER TABLE extra DROP FOREIGN KEY FK_4D3F0D652ADD6D8C');
        $this->addSql('ALTER TABLE extra DROP FOREIGN KEY FK_4D3F0D6564D218E');
        $this->addSql('ALTER TABLE extra_zone DROP FOREIGN KEY FK_349603192B959FC6');
        $this->addSql('ALTER TABLE extra_zone DROP FOREIGN KEY FK_349603199F2C3FAB');
        $this->addSql('DROP TABLE extra');
        $this->addSql('DROP TABLE extra_zone');
        $this->addSql('DROP INDEX IDX_14D431322B959FC6 ON media_object');
        $this->addSql('ALTER TABLE media_object DROP extra_id');
    }
}
