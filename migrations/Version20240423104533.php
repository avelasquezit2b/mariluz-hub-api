<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240423104533 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE extra ADD seo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE extra ADD CONSTRAINT FK_4D3F0D6597E3DD86 FOREIGN KEY (seo_id) REFERENCES seo (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4D3F0D6597E3DD86 ON extra (seo_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE extra DROP FOREIGN KEY FK_4D3F0D6597E3DD86');
        $this->addSql('DROP INDEX UNIQ_4D3F0D6597E3DD86 ON extra');
        $this->addSql('ALTER TABLE extra DROP seo_id');
    }
}
