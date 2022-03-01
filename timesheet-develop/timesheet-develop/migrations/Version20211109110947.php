<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211109110947 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Amend Unique key on tblGitBranch';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE git_project CHANGE dtmCreatedAt dtmCreatedAt DATETIME NOT NULL');
        $this->addSql('DROP INDEX UK_tblGitBranch_strBranchName ON tblGitBranch');
        $this->addSql('CREATE UNIQUE INDEX UK_tblGitBranch_strBranchName_intGitProjectId ON tblGitBranch (strBranchName, intGitProjectId)');
        $this->addSql('ALTER TABLE tracker_history_change_log CHANGE dtmCreatedAt dtmCreatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE tracker_project CHANGE dtmCreatedAt dtmCreatedAt DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE git_project CHANGE dtmCreatedAt dtmCreatedAt DATETIME DEFAULT NULL');
        $this->addSql('DROP INDEX UK_tblGitBranch_strBranchName_intGitProjectId ON tblGitBranch');
        $this->addSql('CREATE UNIQUE INDEX UK_tblGitBranch_strBranchName ON tblGitBranch (strBranchName)');
        $this->addSql('ALTER TABLE tracker_history_change_log CHANGE dtmCreatedAt dtmCreatedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE tracker_project CHANGE dtmCreatedAt dtmCreatedAt DATETIME DEFAULT NULL');
    }
}
