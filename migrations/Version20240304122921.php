<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240304122921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pack (id INT AUTO_INCREMENT NOT NULL, location_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, short_description VARCHAR(255) DEFAULT NULL, extended_description LONGTEXT DEFAULT NULL, highlights LONGTEXT DEFAULT NULL, includes LONGTEXT DEFAULT NULL, not_includes LONGTEXT DEFAULT NULL, carry LONGTEXT DEFAULT NULL, important_information LONGTEXT DEFAULT NULL, cancelation_conditions LONGTEXT DEFAULT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_97DE5E2364D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pack_category (pack_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_28C35DD01919B217 (pack_id), INDEX IDX_28C35DD012469DE2 (category_id), PRIMARY KEY(pack_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pack_subcategory (pack_id INT NOT NULL, subcategory_id INT NOT NULL, INDEX IDX_F92A79911919B217 (pack_id), INDEX IDX_F92A79915DC6FE57 (subcategory_id), PRIMARY KEY(pack_id, subcategory_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pack_zone (pack_id INT NOT NULL, zone_id INT NOT NULL, INDEX IDX_E96A99BE1919B217 (pack_id), INDEX IDX_E96A99BE9F2C3FAB (zone_id), PRIMARY KEY(pack_id, zone_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pack_product_list_module (pack_id INT NOT NULL, product_list_module_id INT NOT NULL, INDEX IDX_94E279321919B217 (pack_id), INDEX IDX_94E27932B167C7D6 (product_list_module_id), PRIMARY KEY(pack_id, product_list_module_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pack ADD CONSTRAINT FK_97DE5E2364D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE pack_category ADD CONSTRAINT FK_28C35DD01919B217 FOREIGN KEY (pack_id) REFERENCES pack (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pack_category ADD CONSTRAINT FK_28C35DD012469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pack_subcategory ADD CONSTRAINT FK_F92A79911919B217 FOREIGN KEY (pack_id) REFERENCES pack (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pack_subcategory ADD CONSTRAINT FK_F92A79915DC6FE57 FOREIGN KEY (subcategory_id) REFERENCES subcategory (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pack_zone ADD CONSTRAINT FK_E96A99BE1919B217 FOREIGN KEY (pack_id) REFERENCES pack (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pack_zone ADD CONSTRAINT FK_E96A99BE9F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pack_product_list_module ADD CONSTRAINT FK_94E279321919B217 FOREIGN KEY (pack_id) REFERENCES pack (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pack_product_list_module ADD CONSTRAINT FK_94E27932B167C7D6 FOREIGN KEY (product_list_module_id) REFERENCES product_list_module (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media_object ADD pack_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media_object ADD CONSTRAINT FK_14D431321919B217 FOREIGN KEY (pack_id) REFERENCES pack (id)');
        $this->addSql('CREATE INDEX IDX_14D431321919B217 ON media_object (pack_id)');
        $this->addSql('ALTER TABLE modality ADD pack_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE modality ADD CONSTRAINT FK_307988C01919B217 FOREIGN KEY (pack_id) REFERENCES pack (id)');
        $this->addSql('CREATE INDEX IDX_307988C01919B217 ON modality (pack_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_object DROP FOREIGN KEY FK_14D431321919B217');
        $this->addSql('ALTER TABLE modality DROP FOREIGN KEY FK_307988C01919B217');
        $this->addSql('ALTER TABLE pack DROP FOREIGN KEY FK_97DE5E2364D218E');
        $this->addSql('ALTER TABLE pack_category DROP FOREIGN KEY FK_28C35DD01919B217');
        $this->addSql('ALTER TABLE pack_category DROP FOREIGN KEY FK_28C35DD012469DE2');
        $this->addSql('ALTER TABLE pack_subcategory DROP FOREIGN KEY FK_F92A79911919B217');
        $this->addSql('ALTER TABLE pack_subcategory DROP FOREIGN KEY FK_F92A79915DC6FE57');
        $this->addSql('ALTER TABLE pack_zone DROP FOREIGN KEY FK_E96A99BE1919B217');
        $this->addSql('ALTER TABLE pack_zone DROP FOREIGN KEY FK_E96A99BE9F2C3FAB');
        $this->addSql('ALTER TABLE pack_product_list_module DROP FOREIGN KEY FK_94E279321919B217');
        $this->addSql('ALTER TABLE pack_product_list_module DROP FOREIGN KEY FK_94E27932B167C7D6');
        $this->addSql('DROP TABLE pack');
        $this->addSql('DROP TABLE pack_category');
        $this->addSql('DROP TABLE pack_subcategory');
        $this->addSql('DROP TABLE pack_zone');
        $this->addSql('DROP TABLE pack_product_list_module');
        $this->addSql('DROP INDEX IDX_14D431321919B217 ON media_object');
        $this->addSql('ALTER TABLE media_object DROP pack_id');
        $this->addSql('DROP INDEX IDX_307988C01919B217 ON modality');
        $this->addSql('ALTER TABLE modality DROP pack_id');
    }
}
