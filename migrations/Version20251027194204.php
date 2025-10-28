<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251027194204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B11CD53EDB6');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B11CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES participant (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B11CD53EDB6');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B11CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES participant (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
