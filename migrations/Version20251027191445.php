<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251027191445 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE participant (id INT AUTO_INCREMENT NOT NULL, session_id INT DEFAULT NULL, receiver_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, email VARCHAR(150) NOT NULL, has_viewed TINYINT(1) NOT NULL, INDEX IDX_D79F6B11613FECDF (session_id), INDEX IDX_D79F6B11CD53EDB6 (receiver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE secret_santa_session (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(10) NOT NULL, is_drawn TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_FA25979C77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B11613FECDF FOREIGN KEY (session_id) REFERENCES secret_santa_session (id)');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B11CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES participant (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B11613FECDF');
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B11CD53EDB6');
        $this->addSql('DROP TABLE participant');
        $this->addSql('DROP TABLE secret_santa_session');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
