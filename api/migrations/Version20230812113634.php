<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230812113634 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_participant ADD player_id INT NOT NULL');
        $this->addSql('ALTER TABLE game_participant ADD CONSTRAINT FK_9CA291399E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9CA291399E6F5DF ON game_participant (player_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE game_participant DROP CONSTRAINT FK_9CA291399E6F5DF');
        $this->addSql('DROP INDEX IDX_9CA291399E6F5DF');
        $this->addSql('ALTER TABLE game_participant DROP player_id');
    }
}
