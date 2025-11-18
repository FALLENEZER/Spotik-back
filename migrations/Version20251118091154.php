<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251118091154 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE track_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE playlist_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE room_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE room_queue_id_seq CASCADE');
        $this->addSql('ALTER TABLE playlist DROP CONSTRAINT fk_d782112d9d86650f');
        $this->addSql('ALTER TABLE room_queue DROP CONSTRAINT fk_3541dec85ed23c43');
        $this->addSql('ALTER TABLE room_queue DROP CONSTRAINT fk_3541dec86bbd148');
        $this->addSql('ALTER TABLE playlist_track DROP CONSTRAINT fk_75ffe1e55ed23c43');
        $this->addSql('ALTER TABLE playlist_track DROP CONSTRAINT fk_75ffe1e56bbd148');
        $this->addSql('ALTER TABLE room_user DROP CONSTRAINT fk_ee973c2d54177093');
        $this->addSql('ALTER TABLE room_user DROP CONSTRAINT fk_ee973c2da76ed395');
        $this->addSql('ALTER TABLE room DROP CONSTRAINT fk_729f519bdc26d3a4');
        $this->addSql('DROP TABLE track');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE playlist');
        $this->addSql('DROP TABLE room_queue');
        $this->addSql('DROP TABLE playlist_track');
        $this->addSql('DROP TABLE room_user');
        $this->addSql('DROP TABLE room');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE track_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE playlist_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE room_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE room_queue_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE track (id SERIAL NOT NULL, spotify_id INT NOT NULL, name VARCHAR(255) NOT NULL, artist VARCHAR(255) DEFAULT NULL, image_url VARCHAR(255) DEFAULT NULL, duration INT NOT NULL, published_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN track.published_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, is_admin BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE playlist (id SERIAL NOT NULL, user_id_id INT NOT NULL, name VARCHAR(255) NOT NULL, published_at DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_d782112d9d86650f ON playlist (user_id_id)');
        $this->addSql('COMMENT ON COLUMN playlist.published_at IS \'(DC2Type:date_immutable)\'');
        $this->addSql('CREATE TABLE room_queue (id SERIAL NOT NULL, track_id INT NOT NULL, playlist_id INT NOT NULL, priority INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_3541dec85ed23c43 ON room_queue (track_id)');
        $this->addSql('CREATE INDEX idx_3541dec86bbd148 ON room_queue (playlist_id)');
        $this->addSql('CREATE TABLE playlist_track (playlist_id INT NOT NULL, track_id INT NOT NULL, PRIMARY KEY(playlist_id, track_id))');
        $this->addSql('CREATE INDEX idx_75ffe1e55ed23c43 ON playlist_track (track_id)');
        $this->addSql('CREATE INDEX idx_75ffe1e56bbd148 ON playlist_track (playlist_id)');
        $this->addSql('CREATE TABLE room_user (room_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(room_id, user_id))');
        $this->addSql('CREATE INDEX idx_ee973c2d54177093 ON room_user (room_id)');
        $this->addSql('CREATE INDEX idx_ee973c2da76ed395 ON room_user (user_id)');
        $this->addSql('CREATE TABLE room (id SERIAL NOT NULL, host_id_id INT NOT NULL, name VARCHAR(255) NOT NULL, published_at DATE NOT NULL, max_users INT NOT NULL, is_private BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_729f519bdc26d3a4 ON room (host_id_id)');
        $this->addSql('COMMENT ON COLUMN room.published_at IS \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE playlist ADD CONSTRAINT fk_d782112d9d86650f FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE room_queue ADD CONSTRAINT fk_3541dec85ed23c43 FOREIGN KEY (track_id) REFERENCES track (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE room_queue ADD CONSTRAINT fk_3541dec86bbd148 FOREIGN KEY (playlist_id) REFERENCES playlist (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE playlist_track ADD CONSTRAINT fk_75ffe1e55ed23c43 FOREIGN KEY (track_id) REFERENCES track (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE playlist_track ADD CONSTRAINT fk_75ffe1e56bbd148 FOREIGN KEY (playlist_id) REFERENCES playlist (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE room_user ADD CONSTRAINT fk_ee973c2d54177093 FOREIGN KEY (room_id) REFERENCES room (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE room_user ADD CONSTRAINT fk_ee973c2da76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE room ADD CONSTRAINT fk_729f519bdc26d3a4 FOREIGN KEY (host_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
