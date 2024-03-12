<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240309223440 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE modality_extra (modality_id INT NOT NULL, extra_id INT NOT NULL, INDEX IDX_65250E982D6D889B (modality_id), INDEX IDX_65250E982B959FC6 (extra_id), PRIMARY KEY(modality_id, extra_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE modality_extra ADD CONSTRAINT FK_65250E982D6D889B FOREIGN KEY (modality_id) REFERENCES modality (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE modality_extra ADD CONSTRAINT FK_65250E982B959FC6 FOREIGN KEY (extra_id) REFERENCES extra (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE modality_extra DROP FOREIGN KEY FK_65250E982D6D889B');
        $this->addSql('ALTER TABLE modality_extra DROP FOREIGN KEY FK_65250E982B959FC6');
        $this->addSql('DROP TABLE modality_extra');
    }
}
