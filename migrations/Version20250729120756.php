<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250729120756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE flash ADD categorie_id INT NOT NULL');
        $this->addSql('ALTER TABLE flash ADD CONSTRAINT FK_AFCE5F03BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_AFCE5F03BCF5E72D ON flash (categorie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flash DROP FOREIGN KEY FK_AFCE5F03BCF5E72D');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP INDEX IDX_AFCE5F03BCF5E72D ON flash');
        $this->addSql('ALTER TABLE flash DROP categorie_id');
    }
}
