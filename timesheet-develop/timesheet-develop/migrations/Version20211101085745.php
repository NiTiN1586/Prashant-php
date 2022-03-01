<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211101085745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Made entity Task soft-deleteable';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblTask ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_tblTask_deletedAt ON tblTask (deleted_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_tblTask_deletedAt ON tblTask');
        $this->addSql('ALTER TABLE tblTask DROP deleted_at');
    }
}
