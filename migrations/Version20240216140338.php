<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240216140338 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE module ADD section_id INT DEFAULT NULL, ADD hero_module_id INT DEFAULT NULL, ADD product_list_module_id INT DEFAULT NULL, ADD search_module_id INT DEFAULT NULL, ADD theme_list_module_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT FK_C242628D823E37A FOREIGN KEY (section_id) REFERENCES section (id)');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT FK_C242628776A80FA FOREIGN KEY (hero_module_id) REFERENCES hero_module (id)');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT FK_C242628B167C7D6 FOREIGN KEY (product_list_module_id) REFERENCES product_list_module (id)');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT FK_C24262814D9A539 FOREIGN KEY (search_module_id) REFERENCES search_module (id)');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT FK_C242628240146FD FOREIGN KEY (theme_list_module_id) REFERENCES theme_list_module (id)');
        $this->addSql('CREATE INDEX IDX_C242628D823E37A ON module (section_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C242628776A80FA ON module (hero_module_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C242628B167C7D6 ON module (product_list_module_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C24262814D9A539 ON module (search_module_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C242628240146FD ON module (theme_list_module_id)');
        $this->addSql('ALTER TABLE page ADD web_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB620FE18474D FOREIGN KEY (web_id) REFERENCES web (id)');
        $this->addSql('CREATE INDEX IDX_140AB620FE18474D ON page (web_id)');
        $this->addSql('ALTER TABLE section ADD page_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE section ADD CONSTRAINT FK_2D737AEFC4663E4 FOREIGN KEY (page_id) REFERENCES page (id)');
        $this->addSql('CREATE INDEX IDX_2D737AEFC4663E4 ON section (page_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE module DROP FOREIGN KEY FK_C242628D823E37A');
        $this->addSql('ALTER TABLE module DROP FOREIGN KEY FK_C242628776A80FA');
        $this->addSql('ALTER TABLE module DROP FOREIGN KEY FK_C242628B167C7D6');
        $this->addSql('ALTER TABLE module DROP FOREIGN KEY FK_C24262814D9A539');
        $this->addSql('ALTER TABLE module DROP FOREIGN KEY FK_C242628240146FD');
        $this->addSql('DROP INDEX IDX_C242628D823E37A ON module');
        $this->addSql('DROP INDEX UNIQ_C242628776A80FA ON module');
        $this->addSql('DROP INDEX UNIQ_C242628B167C7D6 ON module');
        $this->addSql('DROP INDEX UNIQ_C24262814D9A539 ON module');
        $this->addSql('DROP INDEX UNIQ_C242628240146FD ON module');
        $this->addSql('ALTER TABLE module DROP section_id, DROP hero_module_id, DROP product_list_module_id, DROP search_module_id, DROP theme_list_module_id');
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB620FE18474D');
        $this->addSql('DROP INDEX IDX_140AB620FE18474D ON page');
        $this->addSql('ALTER TABLE page DROP web_id');
        $this->addSql('ALTER TABLE section DROP FOREIGN KEY FK_2D737AEFC4663E4');
        $this->addSql('DROP INDEX IDX_2D737AEFC4663E4 ON section');
        $this->addSql('ALTER TABLE section DROP page_id');
    }
}
