<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250305150847 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE area (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, infos VARCHAR(255) DEFAULT NULL, lat_gps DOUBLE PRECISION NOT NULL, lng_gps DOUBLE PRECISION NOT NULL, radius DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE badge (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, legend VARCHAR(255) NOT NULL, unlocking_condition VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE challenge (id INT AUTO_INCREMENT NOT NULL, area_id INT NOT NULL, species_to_guess_id INT NOT NULL, type VARCHAR(255) NOT NULL, description VARCHAR(4095) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, hints LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_D7098951BD0F409C (area_id), INDEX IDX_D70989518B9A2F13 (species_to_guess_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE challenge_species (challenge_id INT NOT NULL, species_id INT NOT NULL, INDEX IDX_499FB89798A21AC6 (challenge_id), INDEX IDX_499FB897B2A1D860 (species_id), PRIMARY KEY(challenge_id, species_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, journey_id INT NOT NULL, user_id INT DEFAULT NULL, ongoing_challenge_id INT NOT NULL, completed_areas LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_finished TINYINT(1) NOT NULL, number_of_areas_completed INT NOT NULL, number_of_areas INT NOT NULL, INDEX IDX_232B318CD5C9896F (journey_id), INDEX IDX_232B318CA76ED395 (user_id), UNIQUE INDEX UNIQ_232B318C7FD7F43F (ongoing_challenge_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE glossary (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE journey (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, infos VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE journey_area (journey_id INT NOT NULL, area_id INT NOT NULL, INDEX IDX_C8F8B2DBD5C9896F (journey_id), INDEX IDX_C8F8B2DBBD0F409C (area_id), PRIMARY KEY(journey_id, area_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ongoing_challenge (id INT AUTO_INCREMENT NOT NULL, challenge_id INT NOT NULL, last_hint INT NOT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_2EC8334D98A21AC6 (challenge_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ongoing_challenge_species (ongoing_challenge_id INT NOT NULL, species_id INT NOT NULL, INDEX IDX_791E73207FD7F43F (ongoing_challenge_id), INDEX IDX_791E7320B2A1D860 (species_id), PRIMARY KEY(ongoing_challenge_id, species_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz (id INT AUTO_INCREMENT NOT NULL, species_id INT NOT NULL, question VARCHAR(255) NOT NULL, answers LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', correct_answer VARCHAR(255) NOT NULL, INDEX IDX_A412FA92B2A1D860 (species_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE settings (id INT AUTO_INCREMENT NOT NULL, language VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE species (id INT AUTO_INCREMENT NOT NULL, area_id INT DEFAULT NULL, latin_name VARCHAR(255) NOT NULL, common_name VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) NOT NULL, origin VARCHAR(2047) DEFAULT NULL, characteristics VARCHAR(4095) DEFAULT NULL, utility VARCHAR(2047) DEFAULT NULL, cultivation_condition VARCHAR(4095) DEFAULT NULL, images LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', lat_gps DOUBLE PRECISION NOT NULL, lng_gps DOUBLE PRECISION NOT NULL, INDEX IDX_A50FF712BD0F409C (area_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statistics (id INT AUTO_INCREMENT NOT NULL, wins INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, settings_id INT NOT NULL, glossary_id INT DEFAULT NULL, statistics_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D64959949888 (settings_id), UNIQUE INDEX UNIQ_8D93D6496ABB587D (glossary_id), UNIQUE INDEX UNIQ_8D93D6499A2595B2 (statistics_id), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_badge (user_id INT NOT NULL, badge_id INT NOT NULL, INDEX IDX_1C32B345A76ED395 (user_id), INDEX IDX_1C32B345F7A2C2FC (badge_id), PRIMARY KEY(user_id, badge_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE challenge ADD CONSTRAINT FK_D7098951BD0F409C FOREIGN KEY (area_id) REFERENCES area (id)');
        $this->addSql('ALTER TABLE challenge ADD CONSTRAINT FK_D70989518B9A2F13 FOREIGN KEY (species_to_guess_id) REFERENCES species (id)');
        $this->addSql('ALTER TABLE challenge_species ADD CONSTRAINT FK_499FB89798A21AC6 FOREIGN KEY (challenge_id) REFERENCES challenge (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE challenge_species ADD CONSTRAINT FK_499FB897B2A1D860 FOREIGN KEY (species_id) REFERENCES species (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CD5C9896F FOREIGN KEY (journey_id) REFERENCES journey (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C7FD7F43F FOREIGN KEY (ongoing_challenge_id) REFERENCES ongoing_challenge (id)');
        $this->addSql('ALTER TABLE journey_area ADD CONSTRAINT FK_C8F8B2DBD5C9896F FOREIGN KEY (journey_id) REFERENCES journey (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE journey_area ADD CONSTRAINT FK_C8F8B2DBBD0F409C FOREIGN KEY (area_id) REFERENCES area (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ongoing_challenge ADD CONSTRAINT FK_2EC8334D98A21AC6 FOREIGN KEY (challenge_id) REFERENCES challenge (id)');
        $this->addSql('ALTER TABLE ongoing_challenge_species ADD CONSTRAINT FK_791E73207FD7F43F FOREIGN KEY (ongoing_challenge_id) REFERENCES ongoing_challenge (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ongoing_challenge_species ADD CONSTRAINT FK_791E7320B2A1D860 FOREIGN KEY (species_id) REFERENCES species (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quiz ADD CONSTRAINT FK_A412FA92B2A1D860 FOREIGN KEY (species_id) REFERENCES species (id)');
        $this->addSql('ALTER TABLE species ADD CONSTRAINT FK_A50FF712BD0F409C FOREIGN KEY (area_id) REFERENCES area (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64959949888 FOREIGN KEY (settings_id) REFERENCES settings (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6496ABB587D FOREIGN KEY (glossary_id) REFERENCES glossary (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6499A2595B2 FOREIGN KEY (statistics_id) REFERENCES statistics (id)');
        $this->addSql('ALTER TABLE user_badge ADD CONSTRAINT FK_1C32B345A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_badge ADD CONSTRAINT FK_1C32B345F7A2C2FC FOREIGN KEY (badge_id) REFERENCES badge (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE challenge DROP FOREIGN KEY FK_D7098951BD0F409C');
        $this->addSql('ALTER TABLE challenge DROP FOREIGN KEY FK_D70989518B9A2F13');
        $this->addSql('ALTER TABLE challenge_species DROP FOREIGN KEY FK_499FB89798A21AC6');
        $this->addSql('ALTER TABLE challenge_species DROP FOREIGN KEY FK_499FB897B2A1D860');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CD5C9896F');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CA76ED395');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C7FD7F43F');
        $this->addSql('ALTER TABLE journey_area DROP FOREIGN KEY FK_C8F8B2DBD5C9896F');
        $this->addSql('ALTER TABLE journey_area DROP FOREIGN KEY FK_C8F8B2DBBD0F409C');
        $this->addSql('ALTER TABLE ongoing_challenge DROP FOREIGN KEY FK_2EC8334D98A21AC6');
        $this->addSql('ALTER TABLE ongoing_challenge_species DROP FOREIGN KEY FK_791E73207FD7F43F');
        $this->addSql('ALTER TABLE ongoing_challenge_species DROP FOREIGN KEY FK_791E7320B2A1D860');
        $this->addSql('ALTER TABLE quiz DROP FOREIGN KEY FK_A412FA92B2A1D860');
        $this->addSql('ALTER TABLE species DROP FOREIGN KEY FK_A50FF712BD0F409C');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64959949888');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6496ABB587D');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6499A2595B2');
        $this->addSql('ALTER TABLE user_badge DROP FOREIGN KEY FK_1C32B345A76ED395');
        $this->addSql('ALTER TABLE user_badge DROP FOREIGN KEY FK_1C32B345F7A2C2FC');
        $this->addSql('DROP TABLE area');
        $this->addSql('DROP TABLE badge');
        $this->addSql('DROP TABLE challenge');
        $this->addSql('DROP TABLE challenge_species');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE glossary');
        $this->addSql('DROP TABLE journey');
        $this->addSql('DROP TABLE journey_area');
        $this->addSql('DROP TABLE ongoing_challenge');
        $this->addSql('DROP TABLE ongoing_challenge_species');
        $this->addSql('DROP TABLE quiz');
        $this->addSql('DROP TABLE settings');
        $this->addSql('DROP TABLE species');
        $this->addSql('DROP TABLE statistics');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_badge');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
