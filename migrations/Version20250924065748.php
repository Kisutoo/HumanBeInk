<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250924065748 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tattoo ADD detail_id INT NOT NULL, CHANGE size_id size_id INT DEFAULT NULL, CHANGE color_id color_id INT DEFAULT NULL, CHANGE area_id area_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tattoo ADD CONSTRAINT FK_DEE4C6FD8D003BB FOREIGN KEY (detail_id) REFERENCES detail (id)');
        $this->addSql('CREATE INDEX IDX_DEE4C6FD8D003BB ON tattoo (detail_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tattoo DROP FOREIGN KEY FK_DEE4C6FD8D003BB');
        $this->addSql('DROP INDEX IDX_DEE4C6FD8D003BB ON tattoo');
        $this->addSql('ALTER TABLE tattoo DROP detail_id, CHANGE size_id size_id INT NOT NULL, CHANGE color_id color_id INT NOT NULL, CHANGE area_id area_id INT NOT NULL');
    }
}
