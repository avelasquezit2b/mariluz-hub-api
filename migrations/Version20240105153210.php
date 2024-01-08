<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240105153210 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE modality_language (modality_id INT NOT NULL, language_id INT NOT NULL, INDEX IDX_BF883BA72D6D889B (modality_id), INDEX IDX_BF883BA782F1BAF4 (language_id), PRIMARY KEY(modality_id, language_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE modality_language ADD CONSTRAINT FK_BF883BA72D6D889B FOREIGN KEY (modality_id) REFERENCES modality (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE modality_language ADD CONSTRAINT FK_BF883BA782F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE modality_language DROP FOREIGN KEY FK_BF883BA72D6D889B');
        $this->addSql('ALTER TABLE modality_language DROP FOREIGN KEY FK_BF883BA782F1BAF4');
        $this->addSql('DROP TABLE modality_language');
    }
}
