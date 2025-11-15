<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251109171820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE playlist (id SERIAL NOT NULL, user_id_id INT NOT NULL, name VARCHAR(255) NOT NULL, published_at DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D782112D9D86650F ON playlist (user_id_id)');
        $this->addSql('COMMENT ON COLUMN playlist.published_at IS \'(DC2Type:date_immutable)\'');
        $this->addSql('CREATE TABLE playlist_track (id SERIAL NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE room (id SERIAL NOT NULL, host_id_id INT NOT NULL, name VARCHAR(255) NOT NULL, published_at DATE NOT NULL, max_users INT NOT NULL, is_private BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_729F519BDC26D3A4 ON room (host_id_id)');
        $this->addSql('COMMENT ON COLUMN room.published_at IS \'(DC2Type:date_immutable)\'');
        $this->addSql('CREATE TABLE room_user (room_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(room_id, user_id))');
        $this->addSql('CREATE INDEX IDX_EE973C2D54177093 ON room_user (room_id)');
        $this->addSql('CREATE INDEX IDX_EE973C2DA76ED395 ON room_user (user_id)');
        $this->addSql('ALTER TABLE playlist ADD CONSTRAINT FK_D782112D9D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE room ADD CONSTRAINT FK_729F519BDC26D3A4 FOREIGN KEY (host_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE room_user ADD CONSTRAINT FK_EE973C2D54177093 FOREIGN KEY (room_id) REFERENCES room (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE room_user ADD CONSTRAINT FK_EE973C2DA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE playlist DROP CONSTRAINT FK_D782112D9D86650F');
        $this->addSql('ALTER TABLE room DROP CONSTRAINT FK_729F519BDC26D3A4');
        $this->addSql('ALTER TABLE room_user DROP CONSTRAINT FK_EE973C2D54177093');
        $this->addSql('ALTER TABLE room_user DROP CONSTRAINT FK_EE973C2DA76ED395');
        $this->addSql('DROP TABLE playlist');
        $this->addSql('DROP TABLE playlist_track');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE room_user');
    }
}
