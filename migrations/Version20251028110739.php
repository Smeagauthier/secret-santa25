<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251028110739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B11613FECDF');
        $this->addSql('ALTER TABLE participant DROP email, DROP has_viewed, CHANGE session_id session_id INT NOT NULL, CHANGE name name VARCHAR(255) NOT NULL, CHANGE token token VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B11613FECDF FOREIGN KEY (session_id) REFERENCES secret_santa_session (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B11613FECDF');
        $this->addSql('ALTER TABLE participant ADD email VARCHAR(150) NOT NULL, ADD has_viewed TINYINT(1) NOT NULL, CHANGE session_id session_id INT DEFAULT NULL, CHANGE name name VARCHAR(100) NOT NULL, CHANGE token token VARCHAR(64) NOT NULL');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B11613FECDF FOREIGN KEY (session_id) REFERENCES secret_santa_session (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
