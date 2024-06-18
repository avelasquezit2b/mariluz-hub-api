<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240524090652 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE about_us_module ADD module_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE about_us_module ADD CONSTRAINT FK_22BBDD6FAFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_22BBDD6FAFC2B591 ON about_us_module (module_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE about_us_module DROP FOREIGN KEY FK_22BBDD6FAFC2B591');
        $this->addSql('DROP INDEX UNIQ_22BBDD6FAFC2B591 ON about_us_module');
        $this->addSql('ALTER TABLE about_us_module DROP module_id');
    }
}
