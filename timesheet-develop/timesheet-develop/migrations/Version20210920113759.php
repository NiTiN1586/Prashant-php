<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210920113759 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Implement Entity Changes';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tblActivity (intId INT AUTO_INCREMENT NOT NULL, intDuration INT NOT NULL, strComment VARCHAR(400) DEFAULT NULL, dtmUpdatedAt DATETIME DEFAULT NULL, dtmCreatedAt DATETIME DEFAULT NULL, bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, intTaskId INT NOT NULL, intOwnerId INT NOT NULL, INDEX IDX_7A198205AEAE0B42 (intTaskId), INDEX IDX_7A198205C04C9957 (intOwnerId), PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tblGitLabProject (intId INT AUTO_INCREMENT NOT NULL, strGitLabLink VARCHAR(255) NOT NULL, strAccessToken VARCHAR(255) NOT NULL, dtmCreatedAt DATETIME DEFAULT NULL, bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, intProjectId INT NOT NULL, INDEX IDX_B0B05DA53B45FED0 (intProjectId), PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tblTask (strJiraLink VARCHAR(200) NOT NULL, strDescription VARCHAR(2000) DEFAULT NULL, intId INT AUTO_INCREMENT NOT NULL, strFriendlyName VARCHAR(30) NOT NULL, strHandle VARCHAR(30) NOT NULL, intDisplayOrder INT DEFAULT NULL, dtmCreatedAt DATETIME DEFAULT NULL, intProjectId INT NOT NULL, intAssigneeId INT DEFAULT NULL, intReporterId INT DEFAULT NULL, intStatusId INT NOT NULL, intPriorityId INT NOT NULL, intActivityTypeId INT NOT NULL, INDEX IDX_4FBDB45C3B45FED0 (intProjectId), INDEX IDX_4FBDB45C975EADA1 (intAssigneeId), INDEX IDX_4FBDB45C9603796 (intReporterId), INDEX IDX_4FBDB45C4F39F20C (intStatusId), INDEX IDX_4FBDB45CDA406E3B (intPriorityId), INDEX IDX_4FBDB45C5AE2AC1B (intActivityTypeId), PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ublActivityType (intId INT AUTO_INCREMENT NOT NULL, strFriendlyName VARCHAR(30) NOT NULL, strHandle VARCHAR(30) NOT NULL, intDisplayOrder INT DEFAULT NULL, dtmCreatedAt DATETIME DEFAULT NULL, bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ublPriority (intId INT AUTO_INCREMENT NOT NULL, strFriendlyName VARCHAR(30) NOT NULL, strHandle VARCHAR(30) NOT NULL, intDisplayOrder INT DEFAULT NULL, dtmCreatedAt DATETIME DEFAULT NULL, bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ublStatus (intId INT AUTO_INCREMENT NOT NULL, strFriendlyName VARCHAR(30) NOT NULL, strHandle VARCHAR(30) NOT NULL, intDisplayOrder INT DEFAULT NULL, dtmCreatedAt DATETIME DEFAULT NULL, bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tblActivity ADD CONSTRAINT FK_7A198205AEAE0B42 FOREIGN KEY (intTaskId) REFERENCES tblTask (intId) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tblActivity ADD CONSTRAINT FK_7A198205C04C9957 FOREIGN KEY (intOwnerId) REFERENCES tblWitcherUser (intId) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tblGitLabProject ADD CONSTRAINT FK_B0B05DA53B45FED0 FOREIGN KEY (intProjectId) REFERENCES tblProject (intId) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tblTask ADD CONSTRAINT FK_4FBDB45C3B45FED0 FOREIGN KEY (intProjectId) REFERENCES tblProject (intId) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tblTask ADD CONSTRAINT FK_4FBDB45C975EADA1 FOREIGN KEY (intAssigneeId) REFERENCES tblWitcherUser (intId) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE tblTask ADD CONSTRAINT FK_4FBDB45C9603796 FOREIGN KEY (intReporterId) REFERENCES tblWitcherUser (intId) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE tblTask ADD CONSTRAINT FK_4FBDB45C4F39F20C FOREIGN KEY (intStatusId) REFERENCES ublStatus (intId)');
        $this->addSql('ALTER TABLE tblTask ADD CONSTRAINT FK_4FBDB45CDA406E3B FOREIGN KEY (intPriorityId) REFERENCES ublPriority (intId)');
        $this->addSql('ALTER TABLE tblTask ADD CONSTRAINT FK_4FBDB45C5AE2AC1B FOREIGN KEY (intActivityTypeId) REFERENCES ublActivityType (intId)');
        $this->addSql('DROP TABLE ublTask');
        $this->addSql('ALTER TABLE tblProject ADD strJiraProjectLink VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE tblWitcherUser ADD intGitlabUserId INT DEFAULT NULL, ADD strJiraAccount VARCHAR(128) DEFAULT NULL, ADD strJiraAccessToken VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8144503626B328F0 ON tblWitcherUser (intGitlabUserId)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8144503616F73C1 ON tblWitcherUser (strJiraAccount)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblActivity DROP FOREIGN KEY FK_7A198205AEAE0B42');
        $this->addSql('ALTER TABLE tblTask DROP FOREIGN KEY FK_4FBDB45C5AE2AC1B');
        $this->addSql('ALTER TABLE tblTask DROP FOREIGN KEY FK_4FBDB45CDA406E3B');
        $this->addSql('ALTER TABLE tblTask DROP FOREIGN KEY FK_4FBDB45C4F39F20C');
        $this->addSql('CREATE TABLE ublTask (intId INT AUTO_INCREMENT NOT NULL, strFriendlyName VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, strHandle VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, intDisplayOrder INT DEFAULT NULL, dtmCreatedAt DATETIME DEFAULT NULL, bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE tblActivity');
        $this->addSql('DROP TABLE tblGitLabProject');
        $this->addSql('DROP TABLE tblTask');
        $this->addSql('DROP TABLE ublActivityType');
        $this->addSql('DROP TABLE ublPriority');
        $this->addSql('DROP TABLE ublStatus');
        $this->addSql('ALTER TABLE tblProject DROP strJiraProjectLink');
        $this->addSql('DROP INDEX UNIQ_8144503626B328F0 ON tblWitcherUser');
        $this->addSql('DROP INDEX UNIQ_8144503665C865BB ON tblWitcherUser');
        $this->addSql('ALTER TABLE tblWitcherUser DROP intGitlabUserId, DROP strJiraAccount, DROP strJiraAccessToken');
    }
}
