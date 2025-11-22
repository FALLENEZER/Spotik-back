<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251121203547 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE Playlist_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE Room_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE RoomQueue_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE Track_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE favorite_playlist_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE playlist_track_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE room_queue_vote_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE Playlist (id INT NOT NULL, owner_id INT NOT NULL, name VARCHAR(255) NOT NULL, createdAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, image TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2EF4737B7E3C61F9 ON Playlist (owner_id)');
        $this->addSql('COMMENT ON COLUMN Playlist.createdAt IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE Room (id INT NOT NULL, host_id INT NOT NULL, name VARCHAR(255) NOT NULL, createdAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, maxUsers INT DEFAULT 50 NOT NULL, isPrivate BOOLEAN DEFAULT false NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D2ADFEA51FB8D185 ON Room (host_id)');
        $this->addSql('COMMENT ON COLUMN Room.createdAt IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE room_member (room_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(room_id, user_id))');
        $this->addSql('CREATE INDEX IDX_31AA3CB954177093 ON room_member (room_id)');
        $this->addSql('CREATE INDEX IDX_31AA3CB9A76ED395 ON room_member (user_id)');
        $this->addSql('CREATE TABLE RoomQueue (id INT NOT NULL, room_id INT NOT NULL, track_id INT NOT NULL, playlist_id INT DEFAULT NULL, priority INT DEFAULT 0 NOT NULL, score INT DEFAULT 0 NOT NULL, status VARCHAR(32) DEFAULT \'pending\' NOT NULL, addedAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, startedAt TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, finishedAt TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, addedBy_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2EC48BB54177093 ON RoomQueue (room_id)');
        $this->addSql('CREATE INDEX IDX_2EC48BB5ED23C43 ON RoomQueue (track_id)');
        $this->addSql('CREATE INDEX IDX_2EC48BB6F6EAFC2 ON RoomQueue (addedBy_id)');
        $this->addSql('CREATE INDEX IDX_2EC48BB6BBD148 ON RoomQueue (playlist_id)');
        $this->addSql('COMMENT ON COLUMN RoomQueue.addedAt IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN RoomQueue.startedAt IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN RoomQueue.finishedAt IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE Track (id INT NOT NULL, spotifyId VARCHAR(128) DEFAULT NULL, name VARCHAR(255) NOT NULL, artist VARCHAR(255) DEFAULT NULL, imageUrl VARCHAR(255) DEFAULT NULL, duration INT DEFAULT NULL, releaseDate TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1722D7A2D411B6C4 ON Track (spotifyId)');
        $this->addSql('COMMENT ON COLUMN Track.releaseDate IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE favorite_playlist (id INT NOT NULL, user_id INT NOT NULL, playlist_id INT NOT NULL, addedAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, isDeleted BOOLEAN DEFAULT false NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7DD509DCA76ED395 ON favorite_playlist (user_id)');
        $this->addSql('CREATE INDEX IDX_7DD509DC6BBD148 ON favorite_playlist (playlist_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_favorite_user_playlist ON favorite_playlist (user_id, playlist_id)');
        $this->addSql('COMMENT ON COLUMN favorite_playlist.addedAt IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE playlist_track (id INT NOT NULL, playlist_id INT NOT NULL, track_id INT NOT NULL, addedAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75FFE1E56BBD148 ON playlist_track (playlist_id)');
        $this->addSql('CREATE INDEX IDX_75FFE1E55ED23C43 ON playlist_track (track_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_playlist_track_item ON playlist_track (playlist_id, track_id)');
        $this->addSql('COMMENT ON COLUMN playlist_track.addedAt IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE room_queue_vote (id INT NOT NULL, user_id INT NOT NULL, value INT NOT NULL, votedAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, queueItem_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CBB2BD8E1E00A688 ON room_queue_vote (queueItem_id)');
        $this->addSql('CREATE INDEX IDX_CBB2BD8EA76ED395 ON room_queue_vote (user_id)');
        $this->addSql('COMMENT ON COLUMN room_queue_vote.votedAt IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, isAdmin BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE Playlist ADD CONSTRAINT FK_2EF4737B7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE Room ADD CONSTRAINT FK_D2ADFEA51FB8D185 FOREIGN KEY (host_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE room_member ADD CONSTRAINT FK_31AA3CB954177093 FOREIGN KEY (room_id) REFERENCES Room (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE room_member ADD CONSTRAINT FK_31AA3CB9A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE RoomQueue ADD CONSTRAINT FK_2EC48BB54177093 FOREIGN KEY (room_id) REFERENCES Room (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE RoomQueue ADD CONSTRAINT FK_2EC48BB5ED23C43 FOREIGN KEY (track_id) REFERENCES Track (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE RoomQueue ADD CONSTRAINT FK_2EC48BB6F6EAFC2 FOREIGN KEY (addedBy_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE RoomQueue ADD CONSTRAINT FK_2EC48BB6BBD148 FOREIGN KEY (playlist_id) REFERENCES Playlist (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE favorite_playlist ADD CONSTRAINT FK_7DD509DCA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE favorite_playlist ADD CONSTRAINT FK_7DD509DC6BBD148 FOREIGN KEY (playlist_id) REFERENCES Playlist (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE playlist_track ADD CONSTRAINT FK_75FFE1E56BBD148 FOREIGN KEY (playlist_id) REFERENCES Playlist (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE playlist_track ADD CONSTRAINT FK_75FFE1E55ED23C43 FOREIGN KEY (track_id) REFERENCES Track (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE room_queue_vote ADD CONSTRAINT FK_CBB2BD8E1E00A688 FOREIGN KEY (queueItem_id) REFERENCES RoomQueue (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE room_queue_vote ADD CONSTRAINT FK_CBB2BD8EA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE Playlist_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE Room_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE RoomQueue_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE Track_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE favorite_playlist_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE playlist_track_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE room_queue_vote_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE Playlist DROP CONSTRAINT FK_2EF4737B7E3C61F9');
        $this->addSql('ALTER TABLE Room DROP CONSTRAINT FK_D2ADFEA51FB8D185');
        $this->addSql('ALTER TABLE room_member DROP CONSTRAINT FK_31AA3CB954177093');
        $this->addSql('ALTER TABLE room_member DROP CONSTRAINT FK_31AA3CB9A76ED395');
        $this->addSql('ALTER TABLE RoomQueue DROP CONSTRAINT FK_2EC48BB54177093');
        $this->addSql('ALTER TABLE RoomQueue DROP CONSTRAINT FK_2EC48BB5ED23C43');
        $this->addSql('ALTER TABLE RoomQueue DROP CONSTRAINT FK_2EC48BB6F6EAFC2');
        $this->addSql('ALTER TABLE RoomQueue DROP CONSTRAINT FK_2EC48BB6BBD148');
        $this->addSql('ALTER TABLE favorite_playlist DROP CONSTRAINT FK_7DD509DCA76ED395');
        $this->addSql('ALTER TABLE favorite_playlist DROP CONSTRAINT FK_7DD509DC6BBD148');
        $this->addSql('ALTER TABLE playlist_track DROP CONSTRAINT FK_75FFE1E56BBD148');
        $this->addSql('ALTER TABLE playlist_track DROP CONSTRAINT FK_75FFE1E55ED23C43');
        $this->addSql('ALTER TABLE room_queue_vote DROP CONSTRAINT FK_CBB2BD8E1E00A688');
        $this->addSql('ALTER TABLE room_queue_vote DROP CONSTRAINT FK_CBB2BD8EA76ED395');
        $this->addSql('DROP TABLE Playlist');
        $this->addSql('DROP TABLE Room');
        $this->addSql('DROP TABLE room_member');
        $this->addSql('DROP TABLE RoomQueue');
        $this->addSql('DROP TABLE Track');
        $this->addSql('DROP TABLE favorite_playlist');
        $this->addSql('DROP TABLE playlist_track');
        $this->addSql('DROP TABLE room_queue_vote');
        $this->addSql('DROP TABLE "user"');
    }
}
