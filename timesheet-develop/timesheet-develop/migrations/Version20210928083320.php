<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210928083320 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Altering of table columns.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblGitLabProject DROP FOREIGN KEY FK_B0B05DA53B45FED0');
        $this->addSql('ALTER TABLE tblTask DROP FOREIGN KEY FK_4FBDB45C3B45FED0');
        $this->addSql('CREATE TABLE tblJiraProject (intId INT AUTO_INCREMENT NOT NULL, strName VARCHAR(50) NOT NULL, strDescription VARCHAR(400) DEFAULT NULL, strJiraProjectLink VARCHAR(255) NOT NULL, strJiraProjectKey VARCHAR(255) NOT NULL, bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, dtmCreatedAt DATETIME DEFAULT NULL, intWictherUserId INT DEFAULT NULL, intSaleTypeId INT DEFAULT NULL, INDEX IDX_C7EC18AE7506BE97 (intWictherUserId), INDEX IDX_C7EC18AE63E1464C (intSaleTypeId), PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tblWitcherProject (intId INT AUTO_INCREMENT NOT NULL, strName VARCHAR(50) NOT NULL, strDescription VARCHAR(400) DEFAULT NULL, strConfluenceLink VARCHAR(150) DEFAULT NULL, bolIsBillable TINYINT(1) DEFAULT \'1\' NOT NULL, bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, dtmCreatedAt DATETIME DEFAULT NULL, intBusinessBranchId INT DEFAULT NULL, intWictherUserId INT DEFAULT NULL, intClientId INT NOT NULL, intSaleTypeId INT DEFAULT NULL, intJiraProject INT DEFAULT NULL, INDEX IDX_5708735C096CAA3 (intBusinessBranchId), INDEX IDX_57087357506BE97 (intWictherUserId), INDEX IDX_57087355437EBCA (intClientId), INDEX IDX_570873563E1464C (intSaleTypeId), UNIQUE INDEX UNIQ_5708735860A7660 (intJiraProject), PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tblJiraProject ADD CONSTRAINT FK_C7EC18AE7506BE97 FOREIGN KEY (intWictherUserId) REFERENCES tblWitcherUser (intId) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE tblJiraProject ADD CONSTRAINT FK_C7EC18AE63E1464C FOREIGN KEY (intSaleTypeId) REFERENCES ublProjectSaleType (intId) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE tblWitcherProject ADD CONSTRAINT FK_5708735C096CAA3 FOREIGN KEY (intBusinessBranchId) REFERENCES ublBusinessBranch (intId) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE tblWitcherProject ADD CONSTRAINT FK_57087357506BE97 FOREIGN KEY (intWictherUserId) REFERENCES tblWitcherUser (intId) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE tblWitcherProject ADD CONSTRAINT FK_57087355437EBCA FOREIGN KEY (intClientId) REFERENCES tblClient (intId) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tblWitcherProject ADD CONSTRAINT FK_570873563E1464C FOREIGN KEY (intSaleTypeId) REFERENCES ublProjectSaleType (intId) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE tblWitcherProject ADD CONSTRAINT FK_5708735860A7660 FOREIGN KEY (intJiraProject) REFERENCES tblJiraProject (intId)');
        $this->addSql('DROP TABLE tblProject');
        $this->addSql('DROP INDEX IDX_B0B05DA53B45FED0 ON tblGitLabProject');
        $this->addSql('ALTER TABLE tblGitLabProject CHANGE intprojectid intWitcherProjectId INT NOT NULL');
        $this->addSql('ALTER TABLE tblGitLabProject ADD CONSTRAINT FK_B0B05DA53281966B FOREIGN KEY (intWitcherProjectId) REFERENCES tblWitcherProject (intId) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_B0B05DA53281966B ON tblGitLabProject (intWitcherProjectId)');
        $this->addSql('DROP INDEX IDX_4FBDB45C3B45FED0 ON tblTask');
        $this->addSql('ALTER TABLE tblTask CHANGE intprojectid intWitcherProjectId INT NOT NULL');
        $this->addSql('ALTER TABLE tblTask ADD CONSTRAINT FK_4FBDB45C3281966B FOREIGN KEY (intWitcherProjectId) REFERENCES tblWitcherProject (intId) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_4FBDB45C3281966B ON tblTask (intWitcherProjectId)');
        $this->addSql('ALTER TABLE tblWitcherUser CHANGE intGitlabUserId intGitlabUserId INT NOT NULL, CHANGE strJiraAccount strJiraAccount VARCHAR(128) NOT NULL');
        $this->addSql('ALTER TABLE ublPriority ADD strDescription VARCHAR(200) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblWitcherProject DROP FOREIGN KEY FK_5708735860A7660');
        $this->addSql('ALTER TABLE tblGitLabProject DROP FOREIGN KEY FK_B0B05DA53281966B');
        $this->addSql('ALTER TABLE tblTask DROP FOREIGN KEY FK_4FBDB45C3281966B');
        $this->addSql('CREATE TABLE tblProject (intId INT AUTO_INCREMENT NOT NULL, strName VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, strRepositoryId VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, strConfluenceLink VARCHAR(150) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, bolIsBillable TINYINT(1) DEFAULT \'1\' NOT NULL, dtmCreatedAt DATETIME DEFAULT NULL, bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, intBusinessBranchId INT DEFAULT NULL, intWictherUserId INT DEFAULT NULL, intClientId INT DEFAULT NULL, intSaleTypeId INT DEFAULT NULL, strJiraProjectLink VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, strDescription VARCHAR(400) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_949B674E63E1464C (intSaleTypeId), INDEX IDX_949B674E7506BE97 (intWictherUserId), INDEX IDX_949B674E5437EBCA (intClientId), INDEX IDX_949B674EC096CAA3 (intBusinessBranchId), PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE tblProject ADD CONSTRAINT FK_949B674E5437EBCA FOREIGN KEY (intClientId) REFERENCES tblClient (intId) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tblProject ADD CONSTRAINT FK_949B674E63E1464C FOREIGN KEY (intSaleTypeId) REFERENCES ublProjectSaleType (intId) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE tblProject ADD CONSTRAINT FK_949B674E7506BE97 FOREIGN KEY (intWictherUserId) REFERENCES tblWitcherUser (intId) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE tblProject ADD CONSTRAINT FK_949B674EC096CAA3 FOREIGN KEY (intBusinessBranchId) REFERENCES ublBusinessBranch (intId) ON DELETE SET NULL');
        $this->addSql('DROP TABLE tblJiraProject');
        $this->addSql('DROP TABLE tblWitcherProject');
        $this->addSql('DROP INDEX IDX_B0B05DA53281966B ON tblGitLabProject');
        $this->addSql('ALTER TABLE tblGitLabProject CHANGE intwitcherprojectid intProjectId INT NOT NULL');
        $this->addSql('ALTER TABLE tblGitLabProject ADD CONSTRAINT FK_B0B05DA53B45FED0 FOREIGN KEY (intProjectId) REFERENCES tblProject (intId) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_B0B05DA53B45FED0 ON tblGitLabProject (intProjectId)');
        $this->addSql('DROP INDEX IDX_4FBDB45C3281966B ON tblTask');
        $this->addSql('ALTER TABLE tblTask CHANGE intwitcherprojectid intProjectId INT NOT NULL');
        $this->addSql('ALTER TABLE tblTask ADD CONSTRAINT FK_4FBDB45C3B45FED0 FOREIGN KEY (intProjectId) REFERENCES tblProject (intId) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_4FBDB45C3B45FED0 ON tblTask (intProjectId)');
        $this->addSql('ALTER TABLE tblWitcherUser CHANGE intGitlabUserId intGitlabUserId INT DEFAULT NULL, CHANGE strJiraAccount strJiraAccount VARCHAR(128) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE ublPriority DROP strDescription');
    }
}
