<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240105152525 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity_language DROP FOREIGN KEY FK_74D1C1BB81C06096');
        $this->addSql('ALTER TABLE activity_language DROP FOREIGN KEY FK_74D1C1BB82F1BAF4');
        $this->addSql('DROP TABLE activity_language');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity_language (activity_id INT NOT NULL, language_id INT NOT NULL, INDEX IDX_74D1C1BB81C06096 (activity_id), INDEX IDX_74D1C1BB82F1BAF4 (language_id), PRIMARY KEY(activity_id, language_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE activity_language ADD CONSTRAINT FK_74D1C1BB81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_language ADD CONSTRAINT FK_74D1C1BB82F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
