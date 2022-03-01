<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211101085314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Made entity Activity soft-deleteable';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblActivity ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_tblActivity_deletedAt ON tblActivity (deleted_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_tblActivity_deletedAt ON tblActivity');
        $this->addSql('ALTER TABLE tblActivity DROP deleted_at');
    }
}
