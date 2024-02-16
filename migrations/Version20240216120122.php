<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240216120122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hero_module (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hero_slide (id INT AUTO_INCREMENT NOT NULL, hero_module_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, subtitle VARCHAR(255) DEFAULT NULL, INDEX IDX_EDD0E1A5776A80FA (hero_module_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page (id INT AUTO_INCREMENT NOT NULL, seo_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_140AB62097E3DD86 (seo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_list_module (id INT AUTO_INCREMENT NOT NULL, theme_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, subtitle VARCHAR(255) DEFAULT NULL, product_type VARCHAR(255) DEFAULT NULL, INDEX IDX_39DB7A1959027487 (theme_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_list_module_hotel (product_list_module_id INT NOT NULL, hotel_id INT NOT NULL, INDEX IDX_C43FF9FFB167C7D6 (product_list_module_id), INDEX IDX_C43FF9FF3243BB18 (hotel_id), PRIMARY KEY(product_list_module_id, hotel_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_list_module_activity (product_list_module_id INT NOT NULL, activity_id INT NOT NULL, INDEX IDX_35F63492B167C7D6 (product_list_module_id), INDEX IDX_35F6349281C06096 (activity_id), PRIMARY KEY(product_list_module_id, activity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE search_module (id INT AUTO_INCREMENT NOT NULL, text VARCHAR(255) DEFAULT NULL, button_text VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE section (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, position SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seo (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE theme (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE theme_list_module (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, subtitle VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE theme_list_module_theme (theme_list_module_id INT NOT NULL, theme_id INT NOT NULL, INDEX IDX_E24884DD240146FD (theme_list_module_id), INDEX IDX_E24884DD59027487 (theme_id), PRIMARY KEY(theme_list_module_id, theme_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE web (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hero_slide ADD CONSTRAINT FK_EDD0E1A5776A80FA FOREIGN KEY (hero_module_id) REFERENCES hero_module (id)');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB62097E3DD86 FOREIGN KEY (seo_id) REFERENCES seo (id)');
        $this->addSql('ALTER TABLE product_list_module ADD CONSTRAINT FK_39DB7A1959027487 FOREIGN KEY (theme_id) REFERENCES theme (id)');
        $this->addSql('ALTER TABLE product_list_module_hotel ADD CONSTRAINT FK_C43FF9FFB167C7D6 FOREIGN KEY (product_list_module_id) REFERENCES product_list_module (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_list_module_hotel ADD CONSTRAINT FK_C43FF9FF3243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_list_module_activity ADD CONSTRAINT FK_35F63492B167C7D6 FOREIGN KEY (product_list_module_id) REFERENCES product_list_module (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_list_module_activity ADD CONSTRAINT FK_35F6349281C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE theme_list_module_theme ADD CONSTRAINT FK_E24884DD240146FD FOREIGN KEY (theme_list_module_id) REFERENCES theme_list_module (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE theme_list_module_theme ADD CONSTRAINT FK_E24884DD59027487 FOREIGN KEY (theme_id) REFERENCES theme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hotel ADD seo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hotel ADD CONSTRAINT FK_3535ED997E3DD86 FOREIGN KEY (seo_id) REFERENCES seo (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3535ED997E3DD86 ON hotel (seo_id)');
        $this->addSql('ALTER TABLE media_object ADD hero_module_id INT DEFAULT NULL, ADD hero_slide_id INT DEFAULT NULL, ADD theme_id INT DEFAULT NULL, ADD seo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media_object ADD CONSTRAINT FK_14D43132776A80FA FOREIGN KEY (hero_module_id) REFERENCES hero_module (id)');
        $this->addSql('ALTER TABLE media_object ADD CONSTRAINT FK_14D4313278444A2F FOREIGN KEY (hero_slide_id) REFERENCES hero_slide (id)');
        $this->addSql('ALTER TABLE media_object ADD CONSTRAINT FK_14D4313259027487 FOREIGN KEY (theme_id) REFERENCES theme (id)');
        $this->addSql('ALTER TABLE media_object ADD CONSTRAINT FK_14D4313297E3DD86 FOREIGN KEY (seo_id) REFERENCES seo (id)');
        $this->addSql('CREATE INDEX IDX_14D43132776A80FA ON media_object (hero_module_id)');
        $this->addSql('CREATE INDEX IDX_14D4313278444A2F ON media_object (hero_slide_id)');
        $this->addSql('CREATE INDEX IDX_14D4313259027487 ON media_object (theme_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_14D4313297E3DD86 ON media_object (seo_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_object DROP FOREIGN KEY FK_14D43132776A80FA');
        $this->addSql('ALTER TABLE media_object DROP FOREIGN KEY FK_14D4313278444A2F');
        $this->addSql('ALTER TABLE hotel DROP FOREIGN KEY FK_3535ED997E3DD86');
        $this->addSql('ALTER TABLE media_object DROP FOREIGN KEY FK_14D4313297E3DD86');
        $this->addSql('ALTER TABLE media_object DROP FOREIGN KEY FK_14D4313259027487');
        $this->addSql('ALTER TABLE hero_slide DROP FOREIGN KEY FK_EDD0E1A5776A80FA');
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB62097E3DD86');
        $this->addSql('ALTER TABLE product_list_module DROP FOREIGN KEY FK_39DB7A1959027487');
        $this->addSql('ALTER TABLE product_list_module_hotel DROP FOREIGN KEY FK_C43FF9FFB167C7D6');
        $this->addSql('ALTER TABLE product_list_module_hotel DROP FOREIGN KEY FK_C43FF9FF3243BB18');
        $this->addSql('ALTER TABLE product_list_module_activity DROP FOREIGN KEY FK_35F63492B167C7D6');
        $this->addSql('ALTER TABLE product_list_module_activity DROP FOREIGN KEY FK_35F6349281C06096');
        $this->addSql('ALTER TABLE theme_list_module_theme DROP FOREIGN KEY FK_E24884DD240146FD');
        $this->addSql('ALTER TABLE theme_list_module_theme DROP FOREIGN KEY FK_E24884DD59027487');
        $this->addSql('DROP TABLE hero_module');
        $this->addSql('DROP TABLE hero_slide');
        $this->addSql('DROP TABLE module');
        $this->addSql('DROP TABLE page');
        $this->addSql('DROP TABLE product_list_module');
        $this->addSql('DROP TABLE product_list_module_hotel');
        $this->addSql('DROP TABLE product_list_module_activity');
        $this->addSql('DROP TABLE search_module');
        $this->addSql('DROP TABLE section');
        $this->addSql('DROP TABLE seo');
        $this->addSql('DROP TABLE theme');
        $this->addSql('DROP TABLE theme_list_module');
        $this->addSql('DROP TABLE theme_list_module_theme');
        $this->addSql('DROP TABLE web');
        $this->addSql('DROP INDEX UNIQ_3535ED997E3DD86 ON hotel');
        $this->addSql('ALTER TABLE hotel DROP seo_id');
        $this->addSql('DROP INDEX IDX_14D43132776A80FA ON media_object');
        $this->addSql('DROP INDEX IDX_14D4313278444A2F ON media_object');
        $this->addSql('DROP INDEX IDX_14D4313259027487 ON media_object');
        $this->addSql('DROP INDEX UNIQ_14D4313297E3DD86 ON media_object');
        $this->addSql('ALTER TABLE media_object DROP hero_module_id, DROP hero_slide_id, DROP theme_id, DROP seo_id');
    }
}
