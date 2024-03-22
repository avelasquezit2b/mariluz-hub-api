<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240321123850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pack_fee (id INT AUTO_INCREMENT NOT NULL, modality_id INT DEFAULT NULL, pack_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_7E5790532D6D889B (modality_id), INDEX IDX_7E5790531919B217 (pack_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pack_price (id INT AUTO_INCREMENT NOT NULL, hotel_id INT DEFAULT NULL, activity_id INT DEFAULT NULL, extra_id INT DEFAULT NULL, pack_season_id INT DEFAULT NULL, markup VARCHAR(25) DEFAULT NULL, commission VARCHAR(25) DEFAULT NULL, INDEX IDX_783CA8A83243BB18 (hotel_id), INDEX IDX_783CA8A881C06096 (activity_id), INDEX IDX_783CA8A82B959FC6 (extra_id), INDEX IDX_783CA8A8245EAE67 (pack_season_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pack_season (id INT AUTO_INCREMENT NOT NULL, pack_fee_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, ranges LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_D754EE898CF51BEA (pack_fee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pack_fee ADD CONSTRAINT FK_7E5790532D6D889B FOREIGN KEY (modality_id) REFERENCES modality (id)');
        $this->addSql('ALTER TABLE pack_fee ADD CONSTRAINT FK_7E5790531919B217 FOREIGN KEY (pack_id) REFERENCES pack (id)');
        $this->addSql('ALTER TABLE pack_price ADD CONSTRAINT FK_783CA8A83243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id)');
        $this->addSql('ALTER TABLE pack_price ADD CONSTRAINT FK_783CA8A881C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE pack_price ADD CONSTRAINT FK_783CA8A82B959FC6 FOREIGN KEY (extra_id) REFERENCES extra (id)');
        $this->addSql('ALTER TABLE pack_price ADD CONSTRAINT FK_783CA8A8245EAE67 FOREIGN KEY (pack_season_id) REFERENCES pack_season (id)');
        $this->addSql('ALTER TABLE pack_season ADD CONSTRAINT FK_D754EE898CF51BEA FOREIGN KEY (pack_fee_id) REFERENCES pack_fee (id)');
        $this->addSql('ALTER TABLE media_object ADD external_url VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pack_fee DROP FOREIGN KEY FK_7E5790532D6D889B');
        $this->addSql('ALTER TABLE pack_fee DROP FOREIGN KEY FK_7E5790531919B217');
        $this->addSql('ALTER TABLE pack_price DROP FOREIGN KEY FK_783CA8A83243BB18');
        $this->addSql('ALTER TABLE pack_price DROP FOREIGN KEY FK_783CA8A881C06096');
        $this->addSql('ALTER TABLE pack_price DROP FOREIGN KEY FK_783CA8A82B959FC6');
        $this->addSql('ALTER TABLE pack_price DROP FOREIGN KEY FK_783CA8A8245EAE67');
        $this->addSql('ALTER TABLE pack_season DROP FOREIGN KEY FK_D754EE898CF51BEA');
        $this->addSql('DROP TABLE pack_fee');
        $this->addSql('DROP TABLE pack_price');
        $this->addSql('DROP TABLE pack_season');
        $this->addSql('ALTER TABLE media_object DROP external_url');
    }
}
