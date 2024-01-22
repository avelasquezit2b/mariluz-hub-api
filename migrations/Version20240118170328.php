<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240118170328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hotel (id INT AUTO_INCREMENT NOT NULL, supplier_id INT DEFAULT NULL, location_id INT DEFAULT NULL, services_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, rating VARCHAR(25) DEFAULT NULL, check_in VARCHAR(25) DEFAULT NULL, check_out VARCHAR(25) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, latitude VARCHAR(50) DEFAULT NULL, longitude VARCHAR(50) DEFAULT NULL, short_description VARCHAR(255) DEFAULT NULL, extended_description LONGTEXT DEFAULT NULL, highlights LONGTEXT DEFAULT NULL, important_information LONGTEXT DEFAULT NULL, cancelation_conditions LONGTEXT DEFAULT NULL, is_active TINYINT(1) DEFAULT NULL, INDEX IDX_3535ED92ADD6D8C (supplier_id), INDEX IDX_3535ED964D218E (location_id), UNIQUE INDEX UNIQ_3535ED9AEF5A6C1 (services_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotel_zone (hotel_id INT NOT NULL, zone_id INT NOT NULL, INDEX IDX_172E168F3243BB18 (hotel_id), INDEX IDX_172E168F9F2C3FAB (zone_id), PRIMARY KEY(hotel_id, zone_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotel_services (id INT AUTO_INCREMENT NOT NULL, breakfast_start_time VARCHAR(25) DEFAULT NULL, breakfast_end_time VARCHAR(25) DEFAULT NULL, lunch_start_time VARCHAR(25) DEFAULT NULL, lunch_end_time VARCHAR(25) DEFAULT NULL, brunch_start_time VARCHAR(25) DEFAULT NULL, brunch_end_time VARCHAR(25) DEFAULT NULL, dinner_start_time VARCHAR(25) DEFAULT NULL, dinner_end_time VARCHAR(25) DEFAULT NULL, bar_start_time VARCHAR(25) DEFAULT NULL, bar_end_time VARCHAR(25) DEFAULT NULL, general_observations LONGTEXT DEFAULT NULL, has_full_time_reception TINYINT(1) DEFAULT NULL, has_heating TINYINT(1) DEFAULT NULL, has_air_conditioner TINYINT(1) DEFAULT NULL, has_wi_fi TINYINT(1) DEFAULT NULL, has_elevator TINYINT(1) DEFAULT NULL, is_adapted_reduced_mobility TINYINT(1) DEFAULT NULL, is_pets_allowed TINYINT(1) DEFAULT NULL, has_cradles TINYINT(1) DEFAULT NULL, has_parking TINYINT(1) DEFAULT NULL, has_spa_access TINYINT(1) DEFAULT NULL, has_gym TINYINT(1) DEFAULT NULL, has_restaurant TINYINT(1) DEFAULT NULL, has_bar TINYINT(1) DEFAULT NULL, has_pool_bar TINYINT(1) DEFAULT NULL, has_buffet_breakfast TINYINT(1) DEFAULT NULL, has_room_service TINYINT(1) DEFAULT NULL, has_buffet_meal TINYINT(1) DEFAULT NULL, has_buffet_dinner TINYINT(1) DEFAULT NULL, has_gluten_free_foods TINYINT(1) DEFAULT NULL, has_themed_restaurants TINYINT(1) DEFAULT NULL, has_chill_out TINYINT(1) DEFAULT NULL, has_beach_club TINYINT(1) DEFAULT NULL, has_massages TINYINT(1) DEFAULT NULL, has_sauna TINYINT(1) DEFAULT NULL, has_jacuzzi TINYINT(1) DEFAULT NULL, has_turkish_bath TINYINT(1) DEFAULT NULL, has_game_room TINYINT(1) DEFAULT NULL, has_hairdresser TINYINT(1) DEFAULT NULL, has_stores_at_hotel TINYINT(1) DEFAULT NULL, has_sun_loungers TINYINT(1) DEFAULT NULL, has_super_market TINYINT(1) DEFAULT NULL, has_terrace TINYINT(1) DEFAULT NULL, has_tennis_court TINYINT(1) DEFAULT NULL, has_paddle_court TINYINT(1) DEFAULT NULL, has_soccer_field TINYINT(1) DEFAULT NULL, has_basketball_court TINYINT(1) DEFAULT NULL, has_outdoor_swimming_pool TINYINT(1) DEFAULT NULL, has_children_pool TINYINT(1) DEFAULT NULL, has_children_club TINYINT(1) DEFAULT NULL, has_children_animation TINYINT(1) DEFAULT NULL, has_adult_animation TINYINT(1) DEFAULT NULL, has_room_tv TINYINT(1) DEFAULT NULL, has_room_phone TINYINT(1) DEFAULT NULL, has_room_terrace TINYINT(1) DEFAULT NULL, has_room_hair_dryer TINYINT(1) DEFAULT NULL, has_room_heating TINYINT(1) DEFAULT NULL, has_room_air_conditioner TINYINT(1) DEFAULT NULL, has_room_safe_deposit_box TINYINT(1) DEFAULT NULL, has_room_desk TINYINT(1) DEFAULT NULL, has_room_wi_fi TINYINT(1) DEFAULT NULL, has_room_wc TINYINT(1) DEFAULT NULL, has_room_shower TINYINT(1) DEFAULT NULL, has_room_bathtub TINYINT(1) DEFAULT NULL, has_room_bidet TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotel_services_pension_type (hotel_services_id INT NOT NULL, pension_type_id INT NOT NULL, INDEX IDX_E32E42AC703BECEE (hotel_services_id), INDEX IDX_E32E42AC35C2A214 (pension_type_id), PRIMARY KEY(hotel_services_id, pension_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pension_type (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, code VARCHAR(25) DEFAULT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room_type (id INT AUTO_INCREMENT NOT NULL, hotel_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_EFDABD4D3243BB18 (hotel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hotel ADD CONSTRAINT FK_3535ED92ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE hotel ADD CONSTRAINT FK_3535ED964D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE hotel ADD CONSTRAINT FK_3535ED9AEF5A6C1 FOREIGN KEY (services_id) REFERENCES hotel_services (id)');
        $this->addSql('ALTER TABLE hotel_zone ADD CONSTRAINT FK_172E168F3243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hotel_zone ADD CONSTRAINT FK_172E168F9F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hotel_services_pension_type ADD CONSTRAINT FK_E32E42AC703BECEE FOREIGN KEY (hotel_services_id) REFERENCES hotel_services (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hotel_services_pension_type ADD CONSTRAINT FK_E32E42AC35C2A214 FOREIGN KEY (pension_type_id) REFERENCES pension_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE room_type ADD CONSTRAINT FK_EFDABD4D3243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hotel DROP FOREIGN KEY FK_3535ED92ADD6D8C');
        $this->addSql('ALTER TABLE hotel DROP FOREIGN KEY FK_3535ED964D218E');
        $this->addSql('ALTER TABLE hotel DROP FOREIGN KEY FK_3535ED9AEF5A6C1');
        $this->addSql('ALTER TABLE hotel_zone DROP FOREIGN KEY FK_172E168F3243BB18');
        $this->addSql('ALTER TABLE hotel_zone DROP FOREIGN KEY FK_172E168F9F2C3FAB');
        $this->addSql('ALTER TABLE hotel_services_pension_type DROP FOREIGN KEY FK_E32E42AC703BECEE');
        $this->addSql('ALTER TABLE hotel_services_pension_type DROP FOREIGN KEY FK_E32E42AC35C2A214');
        $this->addSql('ALTER TABLE room_type DROP FOREIGN KEY FK_EFDABD4D3243BB18');
        $this->addSql('DROP TABLE hotel');
        $this->addSql('DROP TABLE hotel_zone');
        $this->addSql('DROP TABLE hotel_services');
        $this->addSql('DROP TABLE hotel_services_pension_type');
        $this->addSql('DROP TABLE pension_type');
        $this->addSql('DROP TABLE room_type');
    }
}
