<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240117001557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE modality ADD min_pax VARCHAR(50) DEFAULT NULL, ADD max_pax VARCHAR(50) DEFAULT NULL, ADD price VARCHAR(25) DEFAULT NULL, ADD duration VARCHAR(25) DEFAULT NULL, DROP min_price, DROP max_price');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE modality ADD min_price VARCHAR(50) DEFAULT NULL, ADD max_price VARCHAR(50) DEFAULT NULL, DROP min_pax, DROP max_pax, DROP price, DROP duration');
    }
}
