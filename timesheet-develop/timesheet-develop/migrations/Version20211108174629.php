<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211108174629 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'rename tblGitLabProject table to git_project as per ticket WITCHER-154';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblGitBranch DROP FOREIGN KEY FK_FF62290F3133CCD4');
        $this->addSql('CREATE TABLE git_project (intId INT AUTO_INCREMENT NOT NULL, strGitLabLink VARCHAR(255) NOT NULL, strGitLabProjectId VARCHAR(255) NOT NULL, bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, dtmCreatedAt DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, intWitcherProjectId INT NOT NULL, INDEX IDX_git_project_bolActive (bolActive), INDEX IDX_git_project_dtmCreatedAt (dtmCreatedAt), INDEX IDX_git_project_intWitcherProjectId (intWitcherProjectId), INDEX IDX_git_project_deletedAt (deleted_at), PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE git_project ADD CONSTRAINT FK_AC0C61CE3281966B FOREIGN KEY (intWitcherProjectId) REFERENCES tblWitcherProject (intId) ON DELETE CASCADE');
        $this->addSql('DROP TABLE tblGitLabProject');
        $this->addSql('DROP INDEX IDX_tblGitBranch_intGitLabProjectId ON tblGitBranch');
        $this->addSql('ALTER TABLE tblGitBranch CHANGE intgitlabprojectid intGitProjectId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tblGitBranch ADD CONSTRAINT FK_FF62290F463FB80A FOREIGN KEY (intGitProjectId) REFERENCES git_project (intId)');
        $this->addSql('CREATE INDEX IDX_tblGitBranch_intGitProjectId ON tblGitBranch (intGitProjectId)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblGitBranch DROP FOREIGN KEY FK_FF62290F463FB80A');
        $this->addSql('CREATE TABLE tblGitLabProject (intId INT AUTO_INCREMENT NOT NULL, strGitLabLink VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, strGitLabProjectId VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, dtmCreatedAt DATETIME DEFAULT NULL, bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, intWitcherProjectId INT NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_B0B05DA53281966B (intWitcherProjectId), INDEX IDX_tblGitLabProject_bolActive (bolActive), INDEX IDX_tblGitLabProject_deletedAt (deleted_at), INDEX IDX_tblGitLabProject_dtmCreatedAt (dtmCreatedAt), PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE tblGitLabProject ADD CONSTRAINT FK_B0B05DA53281966B FOREIGN KEY (intWitcherProjectId) REFERENCES tblWitcherProject (intId) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('DROP TABLE git_project');
        $this->addSql('DROP INDEX IDX_tblGitBranch_intGitProjectId ON tblGitBranch');
        $this->addSql('ALTER TABLE tblGitBranch CHANGE intgitprojectid intGitLabProjectId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tblGitBranch ADD CONSTRAINT FK_FF62290F3133CCD4 FOREIGN KEY (intGitLabProjectId) REFERENCES tblGitLabProject (intId) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_tblGitBranch_intGitLabProjectId ON tblGitBranch (intGitLabProjectId)');
    }
}
