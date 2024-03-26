<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240325112413 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE communication_template (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) DEFAULT NULL, content LONGTEXT DEFAULT NULL, template_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE communication_template_supplier (communication_template_id INT NOT NULL, supplier_id INT NOT NULL, INDEX IDX_30FFF77A2EBAECFC (communication_template_id), INDEX IDX_30FFF77A2ADD6D8C (supplier_id), PRIMARY KEY(communication_template_id, supplier_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE communication_template_client (communication_template_id INT NOT NULL, client_id INT NOT NULL, INDEX IDX_BA3F3ED32EBAECFC (communication_template_id), INDEX IDX_BA3F3ED319EB6921 (client_id), PRIMARY KEY(communication_template_id, client_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE communication_template_supplier ADD CONSTRAINT FK_30FFF77A2EBAECFC FOREIGN KEY (communication_template_id) REFERENCES communication_template (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE communication_template_supplier ADD CONSTRAINT FK_30FFF77A2ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE communication_template_client ADD CONSTRAINT FK_BA3F3ED32EBAECFC FOREIGN KEY (communication_template_id) REFERENCES communication_template (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE communication_template_client ADD CONSTRAINT FK_BA3F3ED319EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE communication_template_supplier DROP FOREIGN KEY FK_30FFF77A2EBAECFC');
        $this->addSql('ALTER TABLE communication_template_supplier DROP FOREIGN KEY FK_30FFF77A2ADD6D8C');
        $this->addSql('ALTER TABLE communication_template_client DROP FOREIGN KEY FK_BA3F3ED32EBAECFC');
        $this->addSql('ALTER TABLE communication_template_client DROP FOREIGN KEY FK_BA3F3ED319EB6921');
        $this->addSql('DROP TABLE communication_template');
        $this->addSql('DROP TABLE communication_template_supplier');
        $this->addSql('DROP TABLE communication_template_client');
    }
}
