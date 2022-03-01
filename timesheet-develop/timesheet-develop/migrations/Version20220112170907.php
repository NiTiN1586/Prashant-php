<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220112170907 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add updated_by, created_by user WITCHER-277';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095ABC06EA63');
        $this->addSql('DROP INDEX IDX_activity_creator ON activity');
        $this->addSql('ALTER TABLE activity ADD updated_by INT DEFAULT NULL, CHANGE creator created_by INT NOT NULL');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095ADE12AB56 FOREIGN KEY (created_by) REFERENCES witcher_user (id)');

        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A16FE72E1 FOREIGN KEY (updated_by) REFERENCES witcher_user (id)');
        $this->addSql('CREATE INDEX IDX_activity_createdBy ON activity (created_by)');
        $this->addSql('CREATE INDEX IDX_activity_updatedBy ON activity (updated_by)');
        $this->addSql('ALTER TABLE client ADD created_by INT NOT NULL, ADD updated_by INT DEFAULT NULL');

        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455DE12AB56 FOREIGN KEY (created_by) REFERENCES witcher_user (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C744045516FE72E1 FOREIGN KEY (updated_by) REFERENCES witcher_user (id)');
        $this->addSql('CREATE INDEX IDX_client_updatedBy ON client (updated_by)');
        $this->addSql('CREATE INDEX IDX_client_createdBy ON client (created_by)');

        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CBC06EA63');
        $this->addSql('DROP INDEX IDX_task_creator ON comment');
        $this->addSql('ALTER TABLE comment ADD updated_by INT DEFAULT NULL, CHANGE creator created_by INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CDE12AB56 FOREIGN KEY (created_by) REFERENCES witcher_user (id)');

        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C16FE72E1 FOREIGN KEY (updated_by) REFERENCES witcher_user (id)');
        $this->addSql('CREATE INDEX IDX_comment_updatedBy ON comment (updated_by)');
        $this->addSql('CREATE INDEX IDX_comment_createdBy ON comment (created_by)');
        $this->addSql('ALTER TABLE git_project ADD created_by INT NOT NULL, ADD updated_by INT DEFAULT NULL');

        $this->addSql('ALTER TABLE git_project ADD CONSTRAINT FK_AC0C61CEDE12AB56 FOREIGN KEY (created_by) REFERENCES witcher_user (id)');
        $this->addSql('ALTER TABLE git_project ADD CONSTRAINT FK_AC0C61CE16FE72E1 FOREIGN KEY (updated_by) REFERENCES witcher_user (id)');
        $this->addSql('CREATE INDEX IDX_git_project_updatedBy ON git_project (updated_by)');
        $this->addSql('CREATE INDEX IDX_git_project_createdBy ON git_project (created_by)');

        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25BC06EA63');
        $this->addSql('DROP INDEX IDX_task_creator ON task');
        $this->addSql('ALTER TABLE task ADD created_by INT NOT NULL, CHANGE creator updated_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25DE12AB56 FOREIGN KEY (created_by) REFERENCES witcher_user (id)');

        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB2516FE72E1 FOREIGN KEY (updated_by) REFERENCES witcher_user (id)');
        $this->addSql('CREATE INDEX IDX_task_reporter_updatedBy ON task (updated_by)');
        $this->addSql('CREATE INDEX IDX_task_reporter_createdBy ON task (created_by)');
        $this->addSql('ALTER TABLE witcher_project DROP FOREIGN KEY FK_70D1B3321DFF0ECA');

        $this->addSql('DROP INDEX IDX_witcher_project_owner ON witcher_project');
        $this->addSql('ALTER TABLE witcher_project ADD created_by INT NOT NULL, CHANGE witcher_user updated_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE witcher_project ADD CONSTRAINT FK_70D1B332DE12AB56 FOREIGN KEY (created_by) REFERENCES witcher_user (id)');
        $this->addSql('ALTER TABLE witcher_project ADD CONSTRAINT FK_70D1B33216FE72E1 FOREIGN KEY (updated_by) REFERENCES witcher_user (id)');
        $this->addSql('CREATE INDEX IDX_witcher_project_updatedBy ON witcher_project (updated_by)');
        $this->addSql('CREATE INDEX IDX_witcher_project_createdBy ON witcher_project (created_by)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095ADE12AB56');
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A16FE72E1');
        $this->addSql('DROP INDEX IDX_activity_createdBy ON activity');
        $this->addSql('DROP INDEX IDX_activity_updatedBy ON activity');

        $this->addSql('ALTER TABLE activity DROP updated_by, CHANGE created_by creator INT NOT NULL');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095ABC06EA63 FOREIGN KEY (creator) REFERENCES witcher_user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_activity_creator ON activity (creator)');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455DE12AB56');

        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C744045516FE72E1');
        $this->addSql('DROP INDEX IDX_client_updatedBy ON client');
        $this->addSql('DROP INDEX IDX_client_createdBy ON client');
        $this->addSql('ALTER TABLE client DROP created_by, DROP updated_by');

        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CDE12AB56');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C16FE72E1');
        $this->addSql('DROP INDEX IDX_comment_updatedBy ON comment');
        $this->addSql('DROP INDEX IDX_comment_createdBy ON comment');

        $this->addSql('ALTER TABLE comment DROP updated_by, CHANGE created_by creator INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CBC06EA63 FOREIGN KEY (creator) REFERENCES witcher_user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_task_creator ON comment (creator)');
        $this->addSql('ALTER TABLE git_project DROP FOREIGN KEY FK_AC0C61CEDE12AB56');

        $this->addSql('ALTER TABLE git_project DROP FOREIGN KEY FK_AC0C61CE16FE72E1');
        $this->addSql('DROP INDEX IDX_git_project_updatedBy ON git_project');
        $this->addSql('DROP INDEX IDX_git_project_createdBy ON git_project');
        $this->addSql('ALTER TABLE git_project DROP created_by, DROP updated_by');

        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25DE12AB56');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB2516FE72E1');
        $this->addSql('DROP INDEX IDX_task_reporter_updatedBy ON task');
        $this->addSql('DROP INDEX IDX_task_reporter_createdBy ON task');

        $this->addSql('ALTER TABLE task DROP created_by, CHANGE updated_by creator INT DEFAULT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25BC06EA63 FOREIGN KEY (creator) REFERENCES witcher_user (id) ON UPDATE NO ACTION ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_task_creator ON task (creator)');
        $this->addSql('ALTER TABLE witcher_project DROP FOREIGN KEY FK_70D1B332DE12AB56');

        $this->addSql('ALTER TABLE witcher_project DROP FOREIGN KEY FK_70D1B33216FE72E1');
        $this->addSql('DROP INDEX IDX_witcher_project_updatedBy ON witcher_project');
        $this->addSql('DROP INDEX IDX_witcher_project_createdBy ON witcher_project');
        $this->addSql('ALTER TABLE witcher_project DROP created_by, CHANGE updated_by witcher_user INT DEFAULT NULL');

        $this->addSql('ALTER TABLE witcher_project ADD CONSTRAINT FK_70D1B3321DFF0ECA FOREIGN KEY (witcher_user) REFERENCES witcher_user (id) ON UPDATE NO ACTION ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_witcher_project_owner ON witcher_project (witcher_user)');
    }
}
