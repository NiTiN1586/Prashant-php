<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211103122712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename ActivityType to TrackerTaskType and updated related relations as per ticket WITCHER-147';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblTask DROP FOREIGN KEY FK_4FBDB45C5AE2AC1B');
        $this->addSql('ALTER TABLE tblWitcherProjectActivityType DROP FOREIGN KEY FK_650105485AE2AC1B');

        $this->addSql('CREATE TABLE tracker_task_type (intId INT AUTO_INCREMENT NOT NULL, strFriendlyName VARCHAR(30) NOT NULL, strHandle VARCHAR(30) NOT NULL, intDisplayOrder INT DEFAULT NULL, dtmCreatedAt DATETIME DEFAULT NULL, bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_tracker_task_type_intDisplayOrder (intDisplayOrder), INDEX IDX_tracker_task_type_dtmCreatedAt (dtmCreatedAt), INDEX IDX_tracker_task_type_bolActive (bolActive), UNIQUE INDEX UK_tracker_task_type_strHandle (strHandle), PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE witcher_project_tracker_task_type (tracker_task_type INT NOT NULL, intId INT AUTO_INCREMENT NOT NULL, bolSubTaskLevel TINYINT(1) DEFAULT \'0\' NOT NULL, intDisplayOrder INT DEFAULT NULL, bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, dtmCreatedAt DATETIME DEFAULT NULL, intWitcherProjectId INT NOT NULL, INDEX IDX_tracker_task_type (tracker_task_type), INDEX IDX_intWitcherProjectId (intWitcherProjectId), UNIQUE INDEX UNIQ_75F249613281966B2312256C (intWitcherProjectId, tracker_task_type), PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE witcher_project_tracker_task_type ADD CONSTRAINT FK_75F249613281966B FOREIGN KEY (intWitcherProjectId) REFERENCES tblWitcherProject (intId) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE witcher_project_tracker_task_type ADD CONSTRAINT FK_75F249612312256C FOREIGN KEY (tracker_task_type) REFERENCES tracker_task_type (intId) ON DELETE CASCADE');

        $this->addSql('DROP TABLE tblWitcherProjectActivityType');
        $this->addSql('DROP TABLE ublActivityType');
        $this->addSql('DROP INDEX IDX_4FBDB45C5AE2AC1B ON tblTask');

        $this->addSql('ALTER TABLE tblTask CHANGE intactivitytypeid witcher_project_tracker_task_type INT NOT NULL');
        $this->addSql('ALTER TABLE tblTask ADD CONSTRAINT FK_4FBDB45C75F24961 FOREIGN KEY (witcher_project_tracker_task_type) REFERENCES witcher_project_tracker_task_type (intId)');
        $this->addSql('CREATE INDEX IDX_tblTask_witcher_project_tracker_task_type ON tblTask (witcher_project_tracker_task_type)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE witcher_project_tracker_task_type DROP FOREIGN KEY FK_75F249612312256C');
        $this->addSql('ALTER TABLE tblTask DROP FOREIGN KEY FK_4FBDB45C75F24961');
        $this->addSql('CREATE TABLE tblWitcherProjectActivityType (intId INT AUTO_INCREMENT NOT NULL, bolSubTaskLevel TINYINT(1) DEFAULT \'0\' NOT NULL, intDisplayOrder INT DEFAULT NULL, bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, dtmCreatedAt DATETIME DEFAULT NULL, intWitcherProjectId INT NOT NULL, intActivityTypeId INT NOT NULL, INDEX IDX_650105483281966B (intWitcherProjectId), INDEX idx_intActivityTypeId (intActivityTypeId), UNIQUE INDEX UNIQ_650105483281966B5AE2AC1B (intWitcherProjectId, intActivityTypeId), PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ublActivityType (intId INT AUTO_INCREMENT NOT NULL, strFriendlyName VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, strHandle VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, intDisplayOrder INT DEFAULT NULL, dtmCreatedAt DATETIME DEFAULT NULL, bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_ublActivityType_bolActive (bolActive), INDEX IDX_ublActivityType_deletedAt (deleted_at), INDEX IDX_ublActivityType_dtmCreatedAt (dtmCreatedAt), INDEX IDX_ublActivityType_intDisplayOrder (intDisplayOrder), UNIQUE INDEX UK_ublActivityType_strHandle (strHandle), PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE tblWitcherProjectActivityType ADD CONSTRAINT FK_650105483281966B FOREIGN KEY (intWitcherProjectId) REFERENCES tblWitcherProject (intId) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tblWitcherProjectActivityType ADD CONSTRAINT FK_650105485AE2AC1B FOREIGN KEY (intActivityTypeId) REFERENCES ublActivityType (intId) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('DROP TABLE tracker_task_type');
        $this->addSql('DROP TABLE witcher_project_tracker_task_type');
        $this->addSql('DROP INDEX IDX_tblTask_witcher_project_tracker_task_type ON tblTask');
        $this->addSql('ALTER TABLE tblTask CHANGE witcher_project_tracker_task_type intActivityTypeId INT NOT NULL');
        $this->addSql('ALTER TABLE tblTask ADD CONSTRAINT FK_4FBDB45C5AE2AC1B FOREIGN KEY (intActivityTypeId) REFERENCES tblWitcherProjectActivityType (intId) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_4FBDB45C5AE2AC1B ON tblTask (intActivityTypeId)');
    }
}
