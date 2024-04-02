<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240329143254 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_tag (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, icon VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity ADD product_tag_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095AD8AE22B5 FOREIGN KEY (product_tag_id) REFERENCES product_tag (id)');
        $this->addSql('CREATE INDEX IDX_AC74095AD8AE22B5 ON activity (product_tag_id)');
        $this->addSql('ALTER TABLE activity_availability ADD max_quota INT NOT NULL');
        $this->addSql('ALTER TABLE hotel ADD product_tag_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hotel ADD CONSTRAINT FK_3535ED9D8AE22B5 FOREIGN KEY (product_tag_id) REFERENCES product_tag (id)');
        $this->addSql('CREATE INDEX IDX_3535ED9D8AE22B5 ON hotel (product_tag_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095AD8AE22B5');
        $this->addSql('ALTER TABLE hotel DROP FOREIGN KEY FK_3535ED9D8AE22B5');
        $this->addSql('DROP TABLE product_tag');
        $this->addSql('DROP INDEX IDX_AC74095AD8AE22B5 ON activity');
        $this->addSql('ALTER TABLE activity DROP product_tag_id');
        $this->addSql('ALTER TABLE activity_availability DROP max_quota');
        $this->addSql('DROP INDEX IDX_3535ED9D8AE22B5 ON hotel');
        $this->addSql('ALTER TABLE hotel DROP product_tag_id');
    }
}
