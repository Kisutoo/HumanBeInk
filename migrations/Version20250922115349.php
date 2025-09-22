<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250922115349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tattoo ADD size_id INT DEFAULT NULL, ADD color_id INT DEFAULT NULL, ADD area_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tattoo ADD CONSTRAINT FK_DEE4C6F498DA827 FOREIGN KEY (size_id) REFERENCES size (id)');
        $this->addSql('ALTER TABLE tattoo ADD CONSTRAINT FK_DEE4C6F7ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id)');
        $this->addSql('ALTER TABLE tattoo ADD CONSTRAINT FK_DEE4C6FBD0F409C FOREIGN KEY (area_id) REFERENCES area (id)');
        $this->addSql('CREATE INDEX IDX_DEE4C6F498DA827 ON tattoo (size_id)');
        $this->addSql('CREATE INDEX IDX_DEE4C6F7ADA1FB5 ON tattoo (color_id)');
        $this->addSql('CREATE INDEX IDX_DEE4C6FBD0F409C ON tattoo (area_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tattoo DROP FOREIGN KEY FK_DEE4C6F498DA827');
        $this->addSql('ALTER TABLE tattoo DROP FOREIGN KEY FK_DEE4C6F7ADA1FB5');
        $this->addSql('ALTER TABLE tattoo DROP FOREIGN KEY FK_DEE4C6FBD0F409C');
        $this->addSql('DROP INDEX IDX_DEE4C6F498DA827 ON tattoo');
        $this->addSql('DROP INDEX IDX_DEE4C6F7ADA1FB5 ON tattoo');
        $this->addSql('DROP INDEX IDX_DEE4C6FBD0F409C ON tattoo');
        $this->addSql('ALTER TABLE tattoo DROP size_id, DROP color_id, DROP area_id');
    }
}
