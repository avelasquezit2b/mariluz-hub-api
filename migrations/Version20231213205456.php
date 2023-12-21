<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231213205456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity_language (activity_id INT NOT NULL, language_id INT NOT NULL, INDEX IDX_74D1C1BB81C06096 (activity_id), INDEX IDX_74D1C1BB82F1BAF4 (language_id), PRIMARY KEY(activity_id, language_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activity_category (activity_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_A646A9CF81C06096 (activity_id), INDEX IDX_A646A9CF12469DE2 (category_id), PRIMARY KEY(activity_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activity_subcategory (activity_id INT NOT NULL, subcategory_id INT NOT NULL, INDEX IDX_352CE4481C06096 (activity_id), INDEX IDX_352CE445DC6FE57 (subcategory_id), PRIMARY KEY(activity_id, subcategory_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activity_activity (activity_source INT NOT NULL, activity_target INT NOT NULL, INDEX IDX_C7EB954DFC9F73 (activity_source), INDEX IDX_C7EB9541419CFFC (activity_target), PRIMARY KEY(activity_source, activity_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activity_fee (id INT AUTO_INCREMENT NOT NULL, modality_id INT DEFAULT NULL, activity_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, duration VARCHAR(25) DEFAULT NULL, price VARCHAR(25) DEFAULT NULL, has_consolidated_quota TINYINT(1) DEFAULT NULL, has_on_request TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_E470FA372D6D889B (modality_id), INDEX IDX_E470FA3781C06096 (activity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client_type (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, code VARCHAR(25) NOT NULL, min_age INT DEFAULT NULL, max_age INT DEFAULT NULL, custom_title VARCHAR(255) DEFAULT NULL, types LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, short_name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE modality (id INT AUTO_INCREMENT NOT NULL, activity_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, price_type VARCHAR(50) NOT NULL, base_price VARCHAR(50) DEFAULT NULL, min_price VARCHAR(50) DEFAULT NULL, max_price VARCHAR(50) DEFAULT NULL, has_pickup TINYINT(1) NOT NULL, INDEX IDX_307988C081C06096 (activity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE modality_client_type (modality_id INT NOT NULL, client_type_id INT NOT NULL, INDEX IDX_BA1444FF2D6D889B (modality_id), INDEX IDX_BA1444FF9771C8EE (client_type_id), PRIMARY KEY(modality_id, client_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE modality_pickup (modality_id INT NOT NULL, pickup_id INT NOT NULL, INDEX IDX_82BAAD5F2D6D889B (modality_id), INDEX IDX_82BAAD5FC26E160B (pickup_id), PRIMARY KEY(modality_id, pickup_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pickup (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, latitude VARCHAR(50) DEFAULT NULL, longitude VARCHAR(50) DEFAULT NULL, description LONGTEXT DEFAULT NULL, is_meeting_point TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subcategory (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_DDCA44812469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE supplier (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, contact_person VARCHAR(255) DEFAULT NULL, contact_email VARCHAR(255) DEFAULT NULL, contact_phone VARCHAR(50) DEFAULT NULL, booking_email VARCHAR(255) DEFAULT NULL, booking_phone VARCHAR(50) DEFAULT NULL, has_whatsapp TINYINT(1) DEFAULT NULL, internal_notes LONGTEXT DEFAULT NULL, legal_name VARCHAR(255) DEFAULT NULL, document_type VARCHAR(25) DEFAULT NULL, document VARCHAR(50) DEFAULT NULL, admin_email VARCHAR(255) DEFAULT NULL, admin_phone VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity_language ADD CONSTRAINT FK_74D1C1BB81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_language ADD CONSTRAINT FK_74D1C1BB82F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_category ADD CONSTRAINT FK_A646A9CF81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_category ADD CONSTRAINT FK_A646A9CF12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_subcategory ADD CONSTRAINT FK_352CE4481C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_subcategory ADD CONSTRAINT FK_352CE445DC6FE57 FOREIGN KEY (subcategory_id) REFERENCES subcategory (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_activity ADD CONSTRAINT FK_C7EB954DFC9F73 FOREIGN KEY (activity_source) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_activity ADD CONSTRAINT FK_C7EB9541419CFFC FOREIGN KEY (activity_target) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_fee ADD CONSTRAINT FK_E470FA372D6D889B FOREIGN KEY (modality_id) REFERENCES modality (id)');
        $this->addSql('ALTER TABLE activity_fee ADD CONSTRAINT FK_E470FA3781C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE modality ADD CONSTRAINT FK_307988C081C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE modality_client_type ADD CONSTRAINT FK_BA1444FF2D6D889B FOREIGN KEY (modality_id) REFERENCES modality (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE modality_client_type ADD CONSTRAINT FK_BA1444FF9771C8EE FOREIGN KEY (client_type_id) REFERENCES client_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE modality_pickup ADD CONSTRAINT FK_82BAAD5F2D6D889B FOREIGN KEY (modality_id) REFERENCES modality (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE modality_pickup ADD CONSTRAINT FK_82BAAD5FC26E160B FOREIGN KEY (pickup_id) REFERENCES pickup (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subcategory ADD CONSTRAINT FK_DDCA44812469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE activity ADD supplier_id INT DEFAULT NULL, ADD is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A2ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('CREATE INDEX IDX_AC74095A2ADD6D8C ON activity (supplier_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A2ADD6D8C');
        $this->addSql('ALTER TABLE activity_language DROP FOREIGN KEY FK_74D1C1BB81C06096');
        $this->addSql('ALTER TABLE activity_language DROP FOREIGN KEY FK_74D1C1BB82F1BAF4');
        $this->addSql('ALTER TABLE activity_category DROP FOREIGN KEY FK_A646A9CF81C06096');
        $this->addSql('ALTER TABLE activity_category DROP FOREIGN KEY FK_A646A9CF12469DE2');
        $this->addSql('ALTER TABLE activity_subcategory DROP FOREIGN KEY FK_352CE4481C06096');
        $this->addSql('ALTER TABLE activity_subcategory DROP FOREIGN KEY FK_352CE445DC6FE57');
        $this->addSql('ALTER TABLE activity_activity DROP FOREIGN KEY FK_C7EB954DFC9F73');
        $this->addSql('ALTER TABLE activity_activity DROP FOREIGN KEY FK_C7EB9541419CFFC');
        $this->addSql('ALTER TABLE activity_fee DROP FOREIGN KEY FK_E470FA372D6D889B');
        $this->addSql('ALTER TABLE activity_fee DROP FOREIGN KEY FK_E470FA3781C06096');
        $this->addSql('ALTER TABLE modality DROP FOREIGN KEY FK_307988C081C06096');
        $this->addSql('ALTER TABLE modality_client_type DROP FOREIGN KEY FK_BA1444FF2D6D889B');
        $this->addSql('ALTER TABLE modality_client_type DROP FOREIGN KEY FK_BA1444FF9771C8EE');
        $this->addSql('ALTER TABLE modality_pickup DROP FOREIGN KEY FK_82BAAD5F2D6D889B');
        $this->addSql('ALTER TABLE modality_pickup DROP FOREIGN KEY FK_82BAAD5FC26E160B');
        $this->addSql('ALTER TABLE subcategory DROP FOREIGN KEY FK_DDCA44812469DE2');
        $this->addSql('DROP TABLE activity_language');
        $this->addSql('DROP TABLE activity_category');
        $this->addSql('DROP TABLE activity_subcategory');
        $this->addSql('DROP TABLE activity_activity');
        $this->addSql('DROP TABLE activity_fee');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE client_type');
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP TABLE modality');
        $this->addSql('DROP TABLE modality_client_type');
        $this->addSql('DROP TABLE modality_pickup');
        $this->addSql('DROP TABLE pickup');
        $this->addSql('DROP TABLE subcategory');
        $this->addSql('DROP TABLE supplier');
        $this->addSql('DROP INDEX IDX_AC74095A2ADD6D8C ON activity');
        $this->addSql('ALTER TABLE activity DROP supplier_id, DROP is_active');
    }
}
