<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260107182454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_1722d7a2d411b6c4');
        $this->addSql('ALTER TABLE track RENAME COLUMN spotifyid TO path');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1722D7A2B548B0F ON track (path)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_1722D7A2B548B0F');
        $this->addSql('ALTER TABLE Track RENAME COLUMN path TO spotifyid');
        $this->addSql('CREATE UNIQUE INDEX uniq_1722d7a2d411b6c4 ON Track (spotifyid)');
    }
}
