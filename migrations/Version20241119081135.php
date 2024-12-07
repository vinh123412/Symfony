<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241119081135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE diachi (id INT AUTO_INCREMENT NOT NULL, phuong_id INT NOT NULL, quan_id INT NOT NULL, thanhpho_id INT NOT NULL, user_id INT NOT NULL, diachi VARCHAR(100) NOT NULL, INDEX IDX_E6C4249F4BA534C2 (phuong_id), INDEX IDX_E6C4249F6A375A7C (quan_id), INDEX IDX_E6C4249F89493BCF (thanhpho_id), INDEX IDX_E6C4249FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phuong (id INT AUTO_INCREMENT NOT NULL, ten_phuong VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quan (id INT AUTO_INCREMENT NOT NULL, ten_quan VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE thanhpho (id INT AUTO_INCREMENT NOT NULL, ten_thanhpho VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, hoten VARCHAR(100) NOT NULL, username VARCHAR(100) NOT NULL, password VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE diachi ADD CONSTRAINT FK_E6C4249F4BA534C2 FOREIGN KEY (phuong_id) REFERENCES phuong (id)');
        $this->addSql('ALTER TABLE diachi ADD CONSTRAINT FK_E6C4249F6A375A7C FOREIGN KEY (quan_id) REFERENCES quan (id)');
        $this->addSql('ALTER TABLE diachi ADD CONSTRAINT FK_E6C4249F89493BCF FOREIGN KEY (thanhpho_id) REFERENCES thanhpho (id)');
        $this->addSql('ALTER TABLE diachi ADD CONSTRAINT FK_E6C4249FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE diachi DROP FOREIGN KEY FK_E6C4249F4BA534C2');
        $this->addSql('ALTER TABLE diachi DROP FOREIGN KEY FK_E6C4249F6A375A7C');
        $this->addSql('ALTER TABLE diachi DROP FOREIGN KEY FK_E6C4249F89493BCF');
        $this->addSql('ALTER TABLE diachi DROP FOREIGN KEY FK_E6C4249FA76ED395');
        $this->addSql('DROP TABLE diachi');
        $this->addSql('DROP TABLE phuong');
        $this->addSql('DROP TABLE quan');
        $this->addSql('DROP TABLE thanhpho');
        $this->addSql('DROP TABLE user');
    }
}
