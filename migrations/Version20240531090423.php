<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240531090423 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hero_slide ADD additional_media_id INT DEFAULT NULL, ADD has_gift_card TINYINT(1) DEFAULT NULL, ADD has_polaroid TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE hero_slide ADD CONSTRAINT FK_EDD0E1A5504E61A3 FOREIGN KEY (additional_media_id) REFERENCES media_object (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EDD0E1A5504E61A3 ON hero_slide (additional_media_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hero_slide DROP FOREIGN KEY FK_EDD0E1A5504E61A3');
        $this->addSql('DROP INDEX UNIQ_EDD0E1A5504E61A3 ON hero_slide');
        $this->addSql('ALTER TABLE hero_slide DROP additional_media_id, DROP has_gift_card, DROP has_polaroid');
    }
}
