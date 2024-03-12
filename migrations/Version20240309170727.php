<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240309170727 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE theme_activity (theme_id INT NOT NULL, activity_id INT NOT NULL, INDEX IDX_E4A1B2D59027487 (theme_id), INDEX IDX_E4A1B2D81C06096 (activity_id), PRIMARY KEY(theme_id, activity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE theme_hotel (theme_id INT NOT NULL, hotel_id INT NOT NULL, INDEX IDX_5B71D53C59027487 (theme_id), INDEX IDX_5B71D53C3243BB18 (hotel_id), PRIMARY KEY(theme_id, hotel_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE theme_activity ADD CONSTRAINT FK_E4A1B2D59027487 FOREIGN KEY (theme_id) REFERENCES theme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE theme_activity ADD CONSTRAINT FK_E4A1B2D81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE theme_hotel ADD CONSTRAINT FK_5B71D53C59027487 FOREIGN KEY (theme_id) REFERENCES theme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE theme_hotel ADD CONSTRAINT FK_5B71D53C3243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hero_slide ADD promo_link VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE room_type ADD price VARCHAR(25) DEFAULT NULL');
        $this->addSql('ALTER TABLE theme ADD slug VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE theme_activity DROP FOREIGN KEY FK_E4A1B2D59027487');
        $this->addSql('ALTER TABLE theme_activity DROP FOREIGN KEY FK_E4A1B2D81C06096');
        $this->addSql('ALTER TABLE theme_hotel DROP FOREIGN KEY FK_5B71D53C59027487');
        $this->addSql('ALTER TABLE theme_hotel DROP FOREIGN KEY FK_5B71D53C3243BB18');
        $this->addSql('DROP TABLE theme_activity');
        $this->addSql('DROP TABLE theme_hotel');
        $this->addSql('ALTER TABLE hero_slide DROP promo_link');
        $this->addSql('ALTER TABLE room_type DROP price');
        $this->addSql('ALTER TABLE theme DROP slug');
    }
}
