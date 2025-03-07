<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250306143212 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE glossary_species (glossary_id INT NOT NULL, species_id INT NOT NULL, INDEX IDX_2AE80AE46ABB587D (glossary_id), INDEX IDX_2AE80AE4B2A1D860 (species_id), PRIMARY KEY(glossary_id, species_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE glossary_species ADD CONSTRAINT FK_2AE80AE46ABB587D FOREIGN KEY (glossary_id) REFERENCES glossary (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE glossary_species ADD CONSTRAINT FK_2AE80AE4B2A1D860 FOREIGN KEY (species_id) REFERENCES species (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE glossary_species DROP FOREIGN KEY FK_2AE80AE46ABB587D');
        $this->addSql('ALTER TABLE glossary_species DROP FOREIGN KEY FK_2AE80AE4B2A1D860');
        $this->addSql('DROP TABLE glossary_species');
    }
}
