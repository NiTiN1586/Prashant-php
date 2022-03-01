<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220113094105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add updated_by, created_by user WITCHER-277';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE team ADD created_by INT NOT NULL, ADD updated_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61FDE12AB56 FOREIGN KEY (created_by) REFERENCES witcher_user (id)');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F16FE72E1 FOREIGN KEY (updated_by) REFERENCES witcher_user (id)');
        $this->addSql('CREATE INDEX IDX_team_createdBy ON team (created_by)');
        $this->addSql('CREATE INDEX IDX_team_updatedBy ON team (updated_by)');

        $this->addSql('ALTER TABLE witcher_project_tracker_task_type ADD created_by INT NOT NULL, ADD updated_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE witcher_project_tracker_task_type ADD CONSTRAINT FK_75F24961DE12AB56 FOREIGN KEY (created_by) REFERENCES witcher_user (id)');
        $this->addSql('ALTER TABLE witcher_project_tracker_task_type ADD CONSTRAINT FK_75F2496116FE72E1 FOREIGN KEY (updated_by) REFERENCES witcher_user (id)');
        $this->addSql('CREATE INDEX IDX_witcher_project_tracker_task_type_createdBy ON witcher_project_tracker_task_type (created_by)');
        $this->addSql('CREATE INDEX IDX_witcher_project_tracker_task_type_updatedBy ON witcher_project_tracker_task_type (updated_by)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61FDE12AB56');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F16FE72E1');
        $this->addSql('DROP INDEX IDX_team_createdBy ON team');
        $this->addSql('DROP INDEX IDX_team_updatedBy ON team');
        $this->addSql('ALTER TABLE team DROP created_by, DROP updated_by');
        $this->addSql('ALTER TABLE witcher_project_tracker_task_type DROP FOREIGN KEY FK_75F24961DE12AB56');
        $this->addSql('ALTER TABLE witcher_project_tracker_task_type DROP FOREIGN KEY FK_75F2496116FE72E1');
        $this->addSql('DROP INDEX IDX_witcher_project_tracker_task_type_createdBy ON witcher_project_tracker_task_type');
        $this->addSql('DROP INDEX IDX_witcher_project_tracker_task_type_updatedBy ON witcher_project_tracker_task_type');
        $this->addSql('ALTER TABLE witcher_project_tracker_task_type DROP created_by, DROP updated_by');
    }
}
