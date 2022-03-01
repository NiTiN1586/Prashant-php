<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211108180026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'rename tblJiraProject table to tracker_project as per ticket WITCHER-154';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblWitcherProject DROP FOREIGN KEY FK_5708735860A7660');
        $this->addSql('CREATE TABLE tracker_project (intId INT AUTO_INCREMENT NOT NULL, strJiraProjectLink VARCHAR(255) NOT NULL, strJiraProjectKey VARCHAR(255) NOT NULL, intJiraProjectId INT NOT NULL, bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, dtmCreatedAt DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_tracker_project_deletedAt (deleted_at), PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE tblJiraProject');
        $this->addSql('DROP INDEX UNIQ_5708735860A7660 ON tblWitcherProject');
        $this->addSql('ALTER TABLE tblWitcherProject CHANGE intjiraproject tracker_project INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tblWitcherProject ADD CONSTRAINT FK_570873578ECAD02 FOREIGN KEY (tracker_project) REFERENCES tracker_project (intId)');
        $this->addSql('CREATE UNIQUE INDEX UK_tblWitcherProject_tracker_project ON tblWitcherProject (tracker_project)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblWitcherProject DROP FOREIGN KEY FK_570873578ECAD02');
        $this->addSql('CREATE TABLE tblJiraProject (intId INT AUTO_INCREMENT NOT NULL, strJiraProjectLink VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, strJiraProjectKey VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, dtmCreatedAt DATETIME DEFAULT NULL, intJiraProjectId INT NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_tblJiraProject_deletedAt (deleted_at), PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE tracker_project');
        $this->addSql('DROP INDEX UK_tblWitcherProject_tracker_project ON tblWitcherProject');
        $this->addSql('ALTER TABLE tblWitcherProject CHANGE tracker_project intJiraProject INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tblWitcherProject ADD CONSTRAINT FK_5708735860A7660 FOREIGN KEY (intJiraProject) REFERENCES tblJiraProject (intId) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5708735860A7660 ON tblWitcherProject (intJiraProject)');
    }
}
