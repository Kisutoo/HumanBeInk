<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250926075343 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE area (id INT AUTO_INCREMENT NOT NULL, name_area VARCHAR(50) NOT NULL, sensibility SMALLINT DEFAULT NULL, multiplicator NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE color (id INT AUTO_INCREMENT NOT NULL, type_color VARCHAR(50) NOT NULL, multiplicator NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE detail (id INT AUTO_INCREMENT NOT NULL, detail_name VARCHAR(50) NOT NULL, multiplicator NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE flash (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, image VARCHAR(255) NOT NULL, taille DOUBLE PRECISION NOT NULL, couleur VARCHAR(50) NOT NULL, nom VARCHAR(50) NOT NULL, INDEX IDX_AFCE5F0312469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE size (id INT AUTO_INCREMENT NOT NULL, size NUMERIC(10, 2) NOT NULL, multiplicator NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tattoo (id INT AUTO_INCREMENT NOT NULL, size_id INT NOT NULL, color_id INT NOT NULL, area_id INT NOT NULL, detail_id INT NOT NULL, base_price NUMERIC(10, 2) NOT NULL, name VARCHAR(100) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, final_price NUMERIC(10, 2) NOT NULL, INDEX IDX_DEE4C6F498DA827 (size_id), INDEX IDX_DEE4C6F7ADA1FB5 (color_id), INDEX IDX_DEE4C6FBD0F409C (area_id), INDEX IDX_DEE4C6FD8D003BB (detail_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, pseudonyme VARCHAR(50) NOT NULL, google_id VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_flash (user_id INT NOT NULL, flash_id INT NOT NULL, INDEX IDX_4D0CA45BA76ED395 (user_id), INDEX IDX_4D0CA45B25F8D5EA (flash_id), PRIMARY KEY(user_id, flash_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE flash ADD CONSTRAINT FK_AFCE5F0312469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE tattoo ADD CONSTRAINT FK_DEE4C6F498DA827 FOREIGN KEY (size_id) REFERENCES size (id)');
        $this->addSql('ALTER TABLE tattoo ADD CONSTRAINT FK_DEE4C6F7ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id)');
        $this->addSql('ALTER TABLE tattoo ADD CONSTRAINT FK_DEE4C6FBD0F409C FOREIGN KEY (area_id) REFERENCES area (id)');
        $this->addSql('ALTER TABLE tattoo ADD CONSTRAINT FK_DEE4C6FD8D003BB FOREIGN KEY (detail_id) REFERENCES detail (id)');
        $this->addSql('ALTER TABLE user_flash ADD CONSTRAINT FK_4D0CA45BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_flash ADD CONSTRAINT FK_4D0CA45B25F8D5EA FOREIGN KEY (flash_id) REFERENCES flash (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flash DROP FOREIGN KEY FK_AFCE5F0312469DE2');
        $this->addSql('ALTER TABLE tattoo DROP FOREIGN KEY FK_DEE4C6F498DA827');
        $this->addSql('ALTER TABLE tattoo DROP FOREIGN KEY FK_DEE4C6F7ADA1FB5');
        $this->addSql('ALTER TABLE tattoo DROP FOREIGN KEY FK_DEE4C6FBD0F409C');
        $this->addSql('ALTER TABLE tattoo DROP FOREIGN KEY FK_DEE4C6FD8D003BB');
        $this->addSql('ALTER TABLE user_flash DROP FOREIGN KEY FK_4D0CA45BA76ED395');
        $this->addSql('ALTER TABLE user_flash DROP FOREIGN KEY FK_4D0CA45B25F8D5EA');
        $this->addSql('DROP TABLE area');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE color');
        $this->addSql('DROP TABLE detail');
        $this->addSql('DROP TABLE flash');
        $this->addSql('DROP TABLE size');
        $this->addSql('DROP TABLE tattoo');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_flash');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
