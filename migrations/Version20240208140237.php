<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240208140237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE room_condition DROP FOREIGN KEY FK_217F682A237860A5');
        $this->addSql('ALTER TABLE hotel_condition DROP FOREIGN KEY FK_39FF981B0AFEE5E');
        $this->addSql('ALTER TABLE hotel_condition DROP FOREIGN KEY FK_39FF981F0897013');
        $this->addSql('DROP TABLE hotel_condition');
        $this->addSql('ALTER TABLE pension_type_price ADD cancellation_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pension_type_price ADD CONSTRAINT FK_21B90FD0B0AFEE5E FOREIGN KEY (cancellation_type_id) REFERENCES cancellation_type (id)');
        $this->addSql('CREATE INDEX IDX_21B90FD0B0AFEE5E ON pension_type_price (cancellation_type_id)');
        $this->addSql('DROP INDEX IDX_217F682A237860A5 ON room_condition');
        $this->addSql('ALTER TABLE room_condition CHANGE hotel_condition_id hotel_season_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE room_condition ADD CONSTRAINT FK_217F682AF0897013 FOREIGN KEY (hotel_season_id) REFERENCES hotel_season (id)');
        $this->addSql('CREATE INDEX IDX_217F682AF0897013 ON room_condition (hotel_season_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hotel_condition (id INT AUTO_INCREMENT NOT NULL, cancellation_type_id INT DEFAULT NULL, hotel_season_id INT DEFAULT NULL, INDEX IDX_39FF981B0AFEE5E (cancellation_type_id), INDEX IDX_39FF981F0897013 (hotel_season_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE hotel_condition ADD CONSTRAINT FK_39FF981B0AFEE5E FOREIGN KEY (cancellation_type_id) REFERENCES cancellation_type (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE hotel_condition ADD CONSTRAINT FK_39FF981F0897013 FOREIGN KEY (hotel_season_id) REFERENCES hotel_season (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE pension_type_price DROP FOREIGN KEY FK_21B90FD0B0AFEE5E');
        $this->addSql('DROP INDEX IDX_21B90FD0B0AFEE5E ON pension_type_price');
        $this->addSql('ALTER TABLE pension_type_price DROP cancellation_type_id');
        $this->addSql('ALTER TABLE room_condition DROP FOREIGN KEY FK_217F682AF0897013');
        $this->addSql('DROP INDEX IDX_217F682AF0897013 ON room_condition');
        $this->addSql('ALTER TABLE room_condition CHANGE hotel_season_id hotel_condition_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE room_condition ADD CONSTRAINT FK_217F682A237860A5 FOREIGN KEY (hotel_condition_id) REFERENCES hotel_condition (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_217F682A237860A5 ON room_condition (hotel_condition_id)');
    }
}
