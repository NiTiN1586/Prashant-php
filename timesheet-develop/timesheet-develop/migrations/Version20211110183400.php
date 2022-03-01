<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211110183400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename indexname and unique key as per new code guideline defined in ticket WITCHER-161';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity_type MODIFY intId INT NOT NULL');

        $this->addSql('DROP INDEX IDX_activity_type_bolActive ON activity_type');

        $this->addSql('DROP INDEX IDX_activity_type_intDisplayOrder ON activity_type');

        $this->addSql('ALTER TABLE activity_type DROP PRIMARY KEY');

        $this->addSql('TRUNCATE TABLE activity_type');

        $this->addSql('ALTER TABLE activity_type ADD friendly_name VARCHAR(30) NOT NULL, ADD handle VARCHAR(30) NOT NULL, DROP strFriendlyName, DROP strHandle, CHANGE intid id INT AUTO_INCREMENT primary key NOT NULL, CHANGE intdisplayorder display_order INT DEFAULT NULL, CHANGE bolactive active TINYINT(1) DEFAULT \'1\' NOT NULL');

        $this->addSql('CREATE INDEX IDX_activity_type_displayOrder ON activity_type (display_order)');

        $this->addSql('CREATE INDEX IDX_activity_type_active ON activity_type (active)');

        $this->addSql('CREATE UNIQUE INDEX UK_activity_type_handle ON activity_type (handle)');

        $this->addSql('ALTER TABLE activity_type RENAME INDEX idx_activity_type_dtmcreatedat TO IDX_activity_type_createdAt');

        $this->addSql('ALTER TABLE company_position RENAME INDEX idx_company_position_dtmcreatedat TO IDX_company_position_createdAt');

        $this->addSql('ALTER TABLE department RENAME INDEX idx_department_dtmcreatedat TO IDX_department_createdAt');

        $this->addSql('ALTER TABLE git_project MODIFY intId INT NOT NULL');

        $this->addSql('DROP INDEX IDX_git_project_bolActive ON git_project');

        $this->addSql('DROP INDEX IDX_git_project_intWitcherProjectId ON git_project');

        $this->addSql('ALTER TABLE git_project DROP PRIMARY KEY');

        $this->addSql('ALTER TABLE git_project ADD git_lab_link VARCHAR(255) NOT NULL, ADD git_lab_project_id VARCHAR(255) NOT NULL, DROP strGitLabLink, DROP strGitLabProjectId, CHANGE intid id INT AUTO_INCREMENT primary key NOT NULL, CHANGE intwitcherprojectid witcher_project INT NOT NULL, CHANGE bolactive active TINYINT(1) DEFAULT \'1\' NOT NULL');

        $this->addSql('ALTER TABLE git_project ADD CONSTRAINT FK_AC0C61CE70D1B332 FOREIGN KEY (witcher_project) REFERENCES witcher_project (id) ON DELETE CASCADE');

        $this->addSql('CREATE INDEX IDX_git_project_active ON git_project (active)');

        $this->addSql('CREATE INDEX IDX_git_project_witcherProject ON git_project (witcher_project)');

        $this->addSql('ALTER TABLE git_project RENAME INDEX idx_git_project_dtmcreatedat TO IDX_git_project_createdAt');

        $this->addSql('ALTER TABLE tracker_history_change_log_item DROP FOREIGN KEY FK_A7985EBBCC188BEF');

        $this->addSql('ALTER TABLE tracker_history_change_log_item MODIFY intId INT NOT NULL');

        $this->addSql('ALTER TABLE tracker_history_change_log_item DROP PRIMARY KEY');

        $this->addSql('ALTER TABLE tracker_history_change_log MODIFY intId INT NOT NULL');

        $this->addSql('DROP INDEX IDX_tracker_history_change_log_intCreatorId ON tracker_history_change_log');

        $this->addSql('DROP INDEX IDX_tracker_history_change_log_intTaskId ON tracker_history_change_log');

        $this->addSql('ALTER TABLE tracker_history_change_log DROP PRIMARY KEY');

        $this->addSql('ALTER TABLE tracker_history_change_log ADD creator INT DEFAULT NULL, ADD external_id INT DEFAULT NULL, DROP intExternalId, DROP intCreatorId, CHANGE intid id INT AUTO_INCREMENT primary key NOT NULL, CHANGE inttaskid task INT NOT NULL');

        $this->addSql('ALTER TABLE tracker_history_change_log ADD CONSTRAINT FK_CC188BEFBC06EA63 FOREIGN KEY (creator) REFERENCES witcher_user (id)');

        $this->addSql('ALTER TABLE tracker_history_change_log ADD CONSTRAINT FK_CC188BEF527EDB25 FOREIGN KEY (task) REFERENCES task (id)');

        $this->addSql('CREATE INDEX IDX_tracker_history_change_log_creator ON tracker_history_change_log (creator)');

        $this->addSql('CREATE INDEX IDX_tracker_history_change_log_task ON tracker_history_change_log (task)');

        $this->addSql('ALTER TABLE tracker_history_change_log_item ADD change_from VARCHAR(2500) DEFAULT NULL, ADD change_to VARCHAR(2500) DEFAULT NULL, DROP strChangeFrom, DROP strChangeTo, CHANGE intid id INT AUTO_INCREMENT primary key NOT NULL, CHANGE strfield field VARCHAR(50) NOT NULL, CHANGE strfieldtype field_type VARCHAR(20) NOT NULL');

        $this->addSql('ALTER TABLE tracker_history_change_log_item ADD CONSTRAINT FK_A7985EBBCC188BEF FOREIGN KEY (tracker_history_change_log) REFERENCES tracker_history_change_log (id)');

        $this->addSql('ALTER TABLE tracker_history_change_log_item RENAME INDEX idx_tracker_history_change_log_item_tracker_history_change_log TO IDX_tracker_history_change_log_item_trackerHistoryChangeLog');

        $this->addSql('ALTER TABLE tracker_project MODIFY intId INT NOT NULL');

        $this->addSql('ALTER TABLE tracker_project DROP PRIMARY KEY');

        $this->addSql('ALTER TABLE tracker_project ADD jira_project_link VARCHAR(255) NOT NULL, ADD jira_project_key VARCHAR(255) NOT NULL, DROP strJiraProjectLink, DROP strJiraProjectKey, CHANGE intid id INT AUTO_INCREMENT primary key NOT NULL, CHANGE intjiraprojectid jira_project_id INT NOT NULL, CHANGE bolactive active TINYINT(1) DEFAULT \'1\' NOT NULL');

        $this->addSql('ALTER TABLE witcher_project_tracker_task_type MODIFY intId INT NOT NULL');

        $this->addSql('ALTER TABLE witcher_project_tracker_task_type DROP FOREIGN KEY FK_75F249612312256C');

        $this->addSql('DROP INDEX IDX_tracker_task_type_bolActive ON tracker_task_type');

        $this->addSql('DROP INDEX IDX_tracker_task_type_intDisplayOrder ON tracker_task_type');

        $this->addSql('DROP INDEX UK_tracker_task_type_strHandle ON tracker_task_type');

        $this->addSql('ALTER TABLE tracker_task_type MODIFY intId INT NOT NULL');

        $this->addSql('ALTER TABLE tracker_task_type DROP PRIMARY KEY');

        $this->addSql('ALTER TABLE tracker_task_type ADD friendly_name VARCHAR(30) NOT NULL, ADD handle VARCHAR(30) NOT NULL, DROP strFriendlyName, DROP strHandle, CHANGE intid id INT AUTO_INCREMENT primary key NOT NULL, CHANGE intdisplayorder display_order INT DEFAULT NULL, CHANGE bolactive active TINYINT(1) DEFAULT \'1\' NOT NULL');

        $this->addSql('CREATE INDEX IDX_tracker_task_type_displayOrder ON tracker_task_type (display_order)');

        $this->addSql('CREATE INDEX IDX_tracker_task_type_active ON tracker_task_type (active)');

        $this->addSql('CREATE UNIQUE INDEX UK_tracker_task_type_handle ON tracker_task_type (handle)');

        $this->addSql('ALTER TABLE tracker_task_type RENAME INDEX idx_tracker_task_type_dtmcreatedat TO IDX_tracker_task_type_createdAt');

        $this->addSql('DROP INDEX IDX_intWitcherProjectId ON witcher_project_tracker_task_type');

        $this->addSql('DROP INDEX UNIQ_75F249613281966B2312256C ON witcher_project_tracker_task_type');

        $this->addSql('ALTER TABLE witcher_project_tracker_task_type DROP PRIMARY KEY');

        $this->addSql('ALTER TABLE witcher_project_tracker_task_type CHANGE intid id INT AUTO_INCREMENT primary key NOT NULL, CHANGE intwitcherprojectid witcher_project INT NOT NULL, CHANGE bolsubtasklevel sub_task_level TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE intdisplayorder display_order INT DEFAULT NULL, CHANGE bolactive active TINYINT(1) DEFAULT \'1\' NOT NULL');

        $this->addSql('ALTER TABLE witcher_project_tracker_task_type ADD CONSTRAINT FK_75F2496170D1B332 FOREIGN KEY (witcher_project) REFERENCES witcher_project (id) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE witcher_project_tracker_task_type ADD CONSTRAINT FK_75F249612312256C FOREIGN KEY (tracker_task_type) REFERENCES tracker_task_type (id) ON DELETE CASCADE');

        $this->addSql('CREATE INDEX IDX_witcher_project_tracker_task_type_witcherProject ON witcher_project_tracker_task_type (witcher_project)');

        $this->addSql('CREATE UNIQUE INDEX UK_witcher_project_tracker_task_type_wP_tTT ON witcher_project_tracker_task_type (witcher_project, tracker_task_type)');

        $this->addSql('ALTER TABLE witcher_project_tracker_task_type RENAME INDEX idx_tracker_task_type TO IDX_witcher_project_tracker_task_type_trackerTaskType');

        $this->addSql('ALTER TABLE witcher_user_department ADD CONSTRAINT FK_7EE9E406102CBBE3 FOREIGN KEY (witcher_user) REFERENCES witcher_user (id)');

        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A8F1A8CBB FOREIGN KEY (activity_type) REFERENCES activity_type (id) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE git_branch ADD CONSTRAINT FK_D7B2FC08AC0C61CE FOREIGN KEY (git_project) REFERENCES git_project (id)');

        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB2575F24961 FOREIGN KEY (witcher_project_tracker_task_type) REFERENCES witcher_project_tracker_task_type (id)');

        $this->addSql('ALTER TABLE witcher_project ADD CONSTRAINT FK_70D1B33278ECAD02 FOREIGN KEY (tracker_project) REFERENCES tracker_project (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity_type MODIFY id INT NOT NULL');

        $this->addSql('DROP INDEX IDX_activity_type_displayOrder ON activity_type');

        $this->addSql('DROP INDEX IDX_activity_type_active ON activity_type');

        $this->addSql('DROP INDEX UK_activity_type_handle ON activity_type');

        $this->addSql('ALTER TABLE activity_type DROP PRIMARY KEY');

        $this->addSql('ALTER TABLE activity_type ADD strFriendlyName VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD strHandle VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP friendly_name, DROP handle, CHANGE id intId INT AUTO_INCREMENT NOT NULL, CHANGE display_order intDisplayOrder INT DEFAULT NULL, CHANGE active bolActive TINYINT(1) DEFAULT \'1\' NOT NULL');

        $this->addSql('CREATE INDEX IDX_activity_type_bolActive ON activity_type (bolActive)');

        $this->addSql('CREATE INDEX IDX_activity_type_intDisplayOrder ON activity_type (intDisplayOrder)');

        $this->addSql('CREATE UNIQUE INDEX UK_activity_type_strHandle ON activity_type (strHandle)');

        $this->addSql('ALTER TABLE activity_type ADD PRIMARY KEY (intId)');

        $this->addSql('ALTER TABLE activity_type RENAME INDEX idx_activity_type_createdat TO IDX_activity_type_dtmCreatedAt');

        $this->addSql('ALTER TABLE company_position RENAME INDEX idx_company_position_createdat TO IDX_company_position_dtmCreatedAt');

        $this->addSql('ALTER TABLE department RENAME INDEX idx_department_createdat TO IDX_department_dtmCreatedAt');

        $this->addSql('ALTER TABLE git_project MODIFY id INT NOT NULL');

        $this->addSql('DROP INDEX IDX_git_project_active ON git_project');

        $this->addSql('DROP INDEX IDX_git_project_witcherProject ON git_project');

        $this->addSql('ALTER TABLE git_project DROP PRIMARY KEY');

        $this->addSql('ALTER TABLE git_project ADD strGitLabLink VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD strGitLabProjectId VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP git_lab_link, DROP git_lab_project_id, CHANGE id intId INT AUTO_INCREMENT NOT NULL, CHANGE active bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE witcher_project intWitcherProjectId INT NOT NULL');

        $this->addSql('ALTER TABLE git_project ADD CONSTRAINT FK_AC0C61CE3281966B FOREIGN KEY (intWitcherProjectId) REFERENCES tblWitcherProject (intId) ON UPDATE NO ACTION ON DELETE CASCADE');

        $this->addSql('CREATE INDEX IDX_git_project_bolActive ON git_project (bolActive)');

        $this->addSql('CREATE INDEX IDX_git_project_intWitcherProjectId ON git_project (intWitcherProjectId)');

        $this->addSql('ALTER TABLE git_project ADD PRIMARY KEY (intId)');

        $this->addSql('ALTER TABLE git_project RENAME INDEX idx_git_project_createdat TO IDX_git_project_dtmCreatedAt');

        $this->addSql('ALTER TABLE tracker_history_change_log MODIFY id INT NOT NULL');

        $this->addSql('DROP INDEX IDX_tracker_history_change_log_creator ON tracker_history_change_log');

        $this->addSql('DROP INDEX IDX_tracker_history_change_log_task ON tracker_history_change_log');

        $this->addSql('ALTER TABLE tracker_history_change_log DROP PRIMARY KEY');

        $this->addSql('ALTER TABLE tracker_history_change_log ADD intExternalId INT DEFAULT NULL, ADD intCreatorId INT DEFAULT NULL, DROP creator, DROP external_id, CHANGE id intId INT AUTO_INCREMENT NOT NULL, CHANGE task intTaskId INT NOT NULL');

        $this->addSql('ALTER TABLE tracker_history_change_log ADD CONSTRAINT FK_CC188BEF736452D1 FOREIGN KEY (intCreatorId) REFERENCES tblWitcherUser (intId) ON UPDATE NO ACTION ON DELETE NO ACTION');

        $this->addSql('ALTER TABLE tracker_history_change_log ADD CONSTRAINT FK_CC188BEFAEAE0B42 FOREIGN KEY (intTaskId) REFERENCES tblTask (intId) ON UPDATE NO ACTION ON DELETE NO ACTION');

        $this->addSql('CREATE INDEX IDX_tracker_history_change_log_intCreatorId ON tracker_history_change_log (intCreatorId)');

        $this->addSql('CREATE INDEX IDX_tracker_history_change_log_intTaskId ON tracker_history_change_log (intTaskId)');

        $this->addSql('ALTER TABLE tracker_history_change_log ADD PRIMARY KEY (intId)');

        $this->addSql('ALTER TABLE tracker_history_change_log_item MODIFY id INT NOT NULL');

        $this->addSql('ALTER TABLE tracker_history_change_log_item DROP FOREIGN KEY FK_A7985EBBCC188BEF');

        $this->addSql('ALTER TABLE tracker_history_change_log_item DROP PRIMARY KEY');

        $this->addSql('ALTER TABLE tracker_history_change_log_item ADD strChangeFrom VARCHAR(2500) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD strChangeTo VARCHAR(2500) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP change_from, DROP change_to, CHANGE id intId INT AUTO_INCREMENT NOT NULL, CHANGE field strField VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE field_type strFieldType VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');

        $this->addSql('ALTER TABLE tracker_history_change_log_item ADD CONSTRAINT FK_A7985EBBCC188BEF FOREIGN KEY (tracker_history_change_log) REFERENCES tracker_history_change_log (intId) ON UPDATE NO ACTION ON DELETE NO ACTION');

        $this->addSql('ALTER TABLE tracker_history_change_log_item ADD PRIMARY KEY (intId)');

        $this->addSql('ALTER TABLE tracker_history_change_log_item RENAME INDEX idx_tracker_history_change_log_item_trackerhistorychangelog TO IDX_tracker_history_change_log_item_tracker_history_change_log');

        $this->addSql('ALTER TABLE tracker_project MODIFY id INT NOT NULL');

        $this->addSql('ALTER TABLE tracker_project DROP PRIMARY KEY');

        $this->addSql('ALTER TABLE tracker_project ADD strJiraProjectLink VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD strJiraProjectKey VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP jira_project_link, DROP jira_project_key, CHANGE id intId INT AUTO_INCREMENT NOT NULL, CHANGE jira_project_id intJiraProjectId INT NOT NULL, CHANGE active bolActive TINYINT(1) DEFAULT \'1\' NOT NULL');

        $this->addSql('ALTER TABLE tracker_project ADD PRIMARY KEY (intId)');

        $this->addSql('ALTER TABLE tracker_task_type MODIFY id INT NOT NULL');

        $this->addSql('DROP INDEX IDX_tracker_task_type_displayOrder ON tracker_task_type');

        $this->addSql('DROP INDEX IDX_tracker_task_type_active ON tracker_task_type');

        $this->addSql('DROP INDEX UK_tracker_task_type_handle ON tracker_task_type');

        $this->addSql('ALTER TABLE tracker_task_type DROP PRIMARY KEY');

        $this->addSql('ALTER TABLE tracker_task_type ADD strFriendlyName VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD strHandle VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP friendly_name, DROP handle, CHANGE id intId INT AUTO_INCREMENT NOT NULL, CHANGE display_order intDisplayOrder INT DEFAULT NULL, CHANGE active bolActive TINYINT(1) DEFAULT \'1\' NOT NULL');

        $this->addSql('CREATE INDEX IDX_tracker_task_type_bolActive ON tracker_task_type (bolActive)');

        $this->addSql('CREATE INDEX IDX_tracker_task_type_intDisplayOrder ON tracker_task_type (intDisplayOrder)');

        $this->addSql('CREATE UNIQUE INDEX UK_tracker_task_type_strHandle ON tracker_task_type (strHandle)');

        $this->addSql('ALTER TABLE tracker_task_type ADD PRIMARY KEY (intId)');

        $this->addSql('ALTER TABLE tracker_task_type RENAME INDEX idx_tracker_task_type_createdat TO IDX_tracker_task_type_dtmCreatedAt');

        $this->addSql('ALTER TABLE witcher_project_tracker_task_type MODIFY id INT NOT NULL');

        $this->addSql('ALTER TABLE witcher_project_tracker_task_type DROP FOREIGN KEY FK_75F249612312256C');

        $this->addSql('DROP INDEX IDX_witcher_project_tracker_task_type_witcherProject ON witcher_project_tracker_task_type');

        $this->addSql('DROP INDEX UK_witcher_project_tracker_task_type_wP_tTT ON witcher_project_tracker_task_type');

        $this->addSql('ALTER TABLE witcher_project_tracker_task_type DROP PRIMARY KEY');

        $this->addSql('ALTER TABLE witcher_project_tracker_task_type CHANGE id intId INT AUTO_INCREMENT NOT NULL, CHANGE sub_task_level bolSubTaskLevel TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE display_order intDisplayOrder INT DEFAULT NULL, CHANGE active bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE witcher_project intWitcherProjectId INT NOT NULL');

        $this->addSql('ALTER TABLE witcher_project_tracker_task_type ADD CONSTRAINT FK_75F249613281966B FOREIGN KEY (intWitcherProjectId) REFERENCES tblWitcherProject (intId) ON UPDATE NO ACTION ON DELETE CASCADE');

        $this->addSql('ALTER TABLE witcher_project_tracker_task_type ADD CONSTRAINT FK_75F249612312256C FOREIGN KEY (tracker_task_type) REFERENCES tracker_task_type (intId) ON UPDATE NO ACTION ON DELETE CASCADE');

        $this->addSql('CREATE INDEX IDX_intWitcherProjectId ON witcher_project_tracker_task_type (intWitcherProjectId)');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_75F249613281966B2312256C ON witcher_project_tracker_task_type (intWitcherProjectId, tracker_task_type)');

        $this->addSql('ALTER TABLE witcher_project_tracker_task_type ADD PRIMARY KEY (intId)');

        $this->addSql('ALTER TABLE witcher_project_tracker_task_type RENAME INDEX idx_witcher_project_tracker_task_type_trackertasktype TO IDX_tracker_task_type');

        $this->addSql('ALTER TABLE witcher_user_department ADD CONSTRAINT FK_witcher_user_department_witcher_user FOREIGN KEY (witcher_user) REFERENCES tblWitcherUser (intId) ON UPDATE NO ACTION ON DELETE NO ACTION');

        $this->addSql('ALTER TABLE tblActivity ADD CONSTRAINT FK_7A1982058F1A8CBB FOREIGN KEY (activity_type) REFERENCES activity_type (intId) ON UPDATE NO ACTION ON DELETE CASCADE');

        $this->addSql('ALTER TABLE tblGitBranch ADD CONSTRAINT FK_FF62290F463FB80A FOREIGN KEY (intGitProjectId) REFERENCES git_project (intId) ON UPDATE NO ACTION ON DELETE NO ACTION');

        $this->addSql('ALTER TABLE tblTask ADD CONSTRAINT FK_4FBDB45C75F24961 FOREIGN KEY (witcher_project_tracker_task_type) REFERENCES witcher_project_tracker_task_type (intId) ON UPDATE NO ACTION ON DELETE NO ACTION');

        $this->addSql('ALTER TABLE tblWitcherProject ADD CONSTRAINT FK_570873578ECAD02 FOREIGN KEY (tracker_project) REFERENCES tracker_project (intId) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }

    public function postUp(Schema $schema): void
    {
        $this->connection->executeQuery("INSERT IGNORE INTO activity_type (friendly_name, handle, display_order, active) VALUES ('Development','DEVELOPMENT', 1, true)");
        $this->connection->executeQuery("INSERT IGNORE INTO activity_type (friendly_name, handle, display_order, active) VALUES ('Debug','DEBUG', 2, true)");
        $this->connection->executeQuery("INSERT IGNORE INTO activity_type (friendly_name, handle, display_order, active) VALUES ('Code Review','CODE_REVIEW', 3, true)");
        $this->connection->executeQuery("INSERT IGNORE INTO activity_type (friendly_name, handle, display_order, active) VALUES ('Pair Programming','PAIR_PROGRAMMING', 4, true)");
        $this->connection->executeQuery("INSERT IGNORE INTO activity_type (friendly_name, handle, display_order, active) VALUES ('Meeting','MEETING', 5, true)");
    }
}
