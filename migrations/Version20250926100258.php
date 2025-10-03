<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250926100258 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tattoo ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE tattoo ADD CONSTRAINT FK_DEE4C6FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_DEE4C6FA76ED395 ON tattoo (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tattoo DROP FOREIGN KEY FK_DEE4C6FA76ED395');
        $this->addSql('DROP INDEX IDX_DEE4C6FA76ED395 ON tattoo');
        $this->addSql('ALTER TABLE tattoo DROP user_id');
    }
}
