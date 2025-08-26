<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250826020638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_flash (user_id INT NOT NULL, flash_id INT NOT NULL, INDEX IDX_4D0CA45BA76ED395 (user_id), INDEX IDX_4D0CA45B25F8D5EA (flash_id), PRIMARY KEY(user_id, flash_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_flash ADD CONSTRAINT FK_4D0CA45BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_flash ADD CONSTRAINT FK_4D0CA45B25F8D5EA FOREIGN KEY (flash_id) REFERENCES flash (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_flash DROP FOREIGN KEY FK_4D0CA45BA76ED395');
        $this->addSql('ALTER TABLE user_flash DROP FOREIGN KEY FK_4D0CA45B25F8D5EA');
        $this->addSql('DROP TABLE user_flash');
    }
}
