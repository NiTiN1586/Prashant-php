<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211118114341 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove EntityCreatedDateTrait and use Timestampable instead as per ticket WITCHER-194';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_activity_createdAt ON activity');

        $this->addSql('ALTER TABLE activity CHANGE updated_at updated_at DATETIME NOT NULL, CHANGE dtmcreatedat created_at DATETIME NOT NULL');

        $this->addSql('CREATE INDEX IDX_activity_createdAt ON activity (created_at)');

        $this->addSql('DROP INDEX IDX_activity_type_createdAt ON activity_type');

        $this->addSql('ALTER TABLE activity_type ADD updated_at DATETIME NOT NULL, CHANGE dtmcreatedat created_at DATETIME NOT NULL');

        $this->addSql('CREATE INDEX IDX_activity_type_createdAt ON activity_type (created_at)');

        $this->addSql('DROP INDEX IDX_business_branch_createdAt ON business_branch');

        $this->addSql('ALTER TABLE business_branch ADD updated_at DATETIME NOT NULL, CHANGE dtmcreatedat created_at DATETIME NOT NULL');

        $this->addSql('CREATE INDEX IDX_business_branch_createdAt ON business_branch (created_at)');

        $this->addSql('DROP INDEX IDX_client_createdAt ON client');

        $this->addSql('ALTER TABLE client ADD updated_at DATETIME NOT NULL, CHANGE dtmcreatedat created_at DATETIME NOT NULL');

        $this->addSql('CREATE INDEX IDX_client_createdAt ON client (created_at)');

        $this->addSql('DROP INDEX IDX_company_position_createdAt ON company_position');

        $this->addSql('ALTER TABLE company_position ADD updated_at DATETIME NOT NULL, CHANGE dtmcreatedat created_at DATETIME NOT NULL');

        $this->addSql('CREATE INDEX IDX_company_position_createdAt ON company_position (created_at)');

        $this->addSql('DROP INDEX IDX_country_createdAt ON country');

        $this->addSql('ALTER TABLE country ADD updated_at DATETIME NOT NULL, CHANGE dtmcreatedat created_at DATETIME NOT NULL');

        $this->addSql('CREATE INDEX IDX_country_createdAt ON country (created_at)');

        $this->addSql('DROP INDEX IDX_currency_createdAt ON currency');

        $this->addSql('ALTER TABLE currency ADD updated_at DATETIME NOT NULL, CHANGE dtmcreatedat created_at DATETIME NOT NULL');

        $this->addSql('CREATE INDEX IDX_currency_createdAt ON currency (created_at)');

        $this->addSql('DROP INDEX IDX_department_createdAt ON department');

        $this->addSql('ALTER TABLE department ADD updated_at DATETIME NOT NULL, CHANGE dtmcreatedat created_at DATETIME NOT NULL');

        $this->addSql('CREATE INDEX IDX_department_createdAt ON department (created_at)');

        $this->addSql('ALTER TABLE git_branch ADD updated_at DATETIME NOT NULL, CHANGE dtmcreatedat created_at DATETIME NOT NULL');

        $this->addSql('DROP INDEX IDX_git_project_createdAt ON git_project');

        $this->addSql('ALTER TABLE git_project ADD updated_at DATETIME NOT NULL, CHANGE dtmcreatedat created_at DATETIME NOT NULL');

        $this->addSql('CREATE INDEX IDX_git_project_createdAt ON git_project (created_at)');

        $this->addSql('DROP INDEX IDX_industry_createdAt ON industry');

        $this->addSql('ALTER TABLE industry ADD updated_at DATETIME NOT NULL, CHANGE dtmcreatedat created_at DATETIME NOT NULL');

        $this->addSql('CREATE INDEX IDX_industry_createdAt ON industry (created_at)');

        $this->addSql('DROP INDEX IDX_priority_createdAt ON priority');

        $this->addSql('ALTER TABLE priority ADD updated_at DATETIME NOT NULL, CHANGE dtmcreatedat created_at DATETIME NOT NULL');

        $this->addSql('CREATE INDEX IDX_priority_createdAt ON priority (created_at)');

        $this->addSql('DROP INDEX IDX_project_sale_type_createdAt ON project_sale_type');

        $this->addSql('ALTER TABLE project_sale_type ADD updated_at DATETIME NOT NULL, CHANGE dtmcreatedat created_at DATETIME NOT NULL');

        $this->addSql('CREATE INDEX IDX_project_sale_type_createdAt ON project_sale_type (created_at)');

        $this->addSql('DROP INDEX IDX_status_createdAt ON status');

        $this->addSql('ALTER TABLE status ADD updated_at DATETIME NOT NULL, CHANGE dtmcreatedat created_at DATETIME NOT NULL');

        $this->addSql('CREATE INDEX IDX_status_createdAt ON status (created_at)');

        $this->addSql('DROP INDEX IDX_task_createdAt ON task');

        $this->addSql('ALTER TABLE task CHANGE updated_at updated_at DATETIME NOT NULL, CHANGE dtmcreatedat created_at DATETIME NOT NULL');

        $this->addSql('CREATE INDEX IDX_task_createdAt ON task (created_at)');

        $this->addSql('DROP INDEX IDX_technology_createdAt ON technology');

        $this->addSql('ALTER TABLE technology ADD updated_at DATETIME NOT NULL, CHANGE dtmcreatedat created_at DATETIME NOT NULL');

        $this->addSql('CREATE INDEX IDX_technology_createdAt ON technology (created_at)');

        $this->addSql('ALTER TABLE tracker_history_change_log ADD updated_at DATETIME NOT NULL, CHANGE dtmcreatedat created_at DATETIME NOT NULL');

        $this->addSql('ALTER TABLE tracker_project ADD updated_at DATETIME NOT NULL, CHANGE dtmcreatedat created_at DATETIME NOT NULL');

        $this->addSql('DROP INDEX IDX_tracker_task_type_createdAt ON tracker_task_type');

        $this->addSql('ALTER TABLE tracker_task_type ADD updated_at DATETIME NOT NULL, CHANGE dtmcreatedat created_at DATETIME NOT NULL');

        $this->addSql('CREATE INDEX IDX_tracker_task_type_createdAt ON tracker_task_type (created_at)');

        $this->addSql('ALTER TABLE witcher_project ADD updated_at DATETIME NOT NULL, CHANGE dtmcreatedat created_at DATETIME NOT NULL');

        $this->addSql('ALTER TABLE witcher_project_tracker_task_type ADD updated_at DATETIME NOT NULL, CHANGE dtmcreatedat created_at DATETIME NOT NULL');

        $this->addSql('DROP INDEX IDX_witcher_user_createdAt ON witcher_user');

        $this->addSql('ALTER TABLE witcher_user ADD updated_at DATETIME NOT NULL, CHANGE dtmcreatedat created_at DATETIME NOT NULL');

        $this->addSql('CREATE INDEX IDX_witcher_user_createdAt ON witcher_user (created_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_activity_createdAt ON activity');

        $this->addSql('ALTER TABLE activity CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE created_at dtmCreatedAt DATETIME NOT NULL');

        $this->addSql('CREATE INDEX IDX_activity_createdAt ON activity (dtmCreatedAt)');

        $this->addSql('DROP INDEX IDX_activity_type_createdAt ON activity_type');

        $this->addSql('ALTER TABLE activity_type ADD dtmCreatedAt DATETIME NOT NULL, DROP created_at, DROP updated_at');

        $this->addSql('CREATE INDEX IDX_activity_type_createdAt ON activity_type (dtmCreatedAt)');

        $this->addSql('DROP INDEX IDX_business_branch_createdAt ON business_branch');

        $this->addSql('ALTER TABLE business_branch ADD dtmCreatedAt DATETIME NOT NULL, DROP created_at, DROP updated_at');

        $this->addSql('CREATE INDEX IDX_business_branch_createdAt ON business_branch (dtmCreatedAt)');

        $this->addSql('DROP INDEX IDX_client_createdAt ON client');

        $this->addSql('ALTER TABLE client ADD dtmCreatedAt DATETIME NOT NULL, DROP created_at, DROP updated_at');

        $this->addSql('CREATE INDEX IDX_client_createdAt ON client (dtmCreatedAt)');

        $this->addSql('DROP INDEX IDX_company_position_createdAt ON company_position');

        $this->addSql('ALTER TABLE company_position ADD dtmCreatedAt DATETIME NOT NULL, DROP created_at, DROP updated_at');

        $this->addSql('CREATE INDEX IDX_company_position_createdAt ON company_position (dtmCreatedAt)');

        $this->addSql('DROP INDEX IDX_country_createdAt ON country');

        $this->addSql('ALTER TABLE country ADD dtmCreatedAt DATETIME NOT NULL, DROP created_at, DROP updated_at');

        $this->addSql('CREATE INDEX IDX_country_createdAt ON country (dtmCreatedAt)');

        $this->addSql('DROP INDEX IDX_currency_createdAt ON currency');

        $this->addSql('ALTER TABLE currency ADD dtmCreatedAt DATETIME NOT NULL, DROP created_at, DROP updated_at');

        $this->addSql('CREATE INDEX IDX_currency_createdAt ON currency (dtmCreatedAt)');

        $this->addSql('DROP INDEX IDX_department_createdAt ON department');

        $this->addSql('ALTER TABLE department ADD dtmCreatedAt DATETIME NOT NULL, DROP created_at, DROP updated_at');

        $this->addSql('CREATE INDEX IDX_department_createdAt ON department (dtmCreatedAt)');

        $this->addSql('ALTER TABLE git_branch ADD dtmCreatedAt DATETIME NOT NULL, DROP created_at, DROP updated_at');

        $this->addSql('DROP INDEX IDX_git_project_createdAt ON git_project');

        $this->addSql('ALTER TABLE git_project ADD dtmCreatedAt DATETIME NOT NULL, DROP created_at, DROP updated_at');

        $this->addSql('CREATE INDEX IDX_git_project_createdAt ON git_project (dtmCreatedAt)');

        $this->addSql('DROP INDEX IDX_industry_createdAt ON industry');

        $this->addSql('ALTER TABLE industry ADD dtmCreatedAt DATETIME NOT NULL, DROP created_at, DROP updated_at');

        $this->addSql('CREATE INDEX IDX_industry_createdAt ON industry (dtmCreatedAt)');

        $this->addSql('DROP INDEX IDX_priority_createdAt ON priority');

        $this->addSql('ALTER TABLE priority ADD dtmCreatedAt DATETIME NOT NULL, DROP created_at, DROP updated_at');

        $this->addSql('CREATE INDEX IDX_priority_createdAt ON priority (dtmCreatedAt)');

        $this->addSql('DROP INDEX IDX_project_sale_type_createdAt ON project_sale_type');

        $this->addSql('ALTER TABLE project_sale_type ADD dtmCreatedAt DATETIME NOT NULL, DROP created_at, DROP updated_at');

        $this->addSql('CREATE INDEX IDX_project_sale_type_createdAt ON project_sale_type (dtmCreatedAt)');

        $this->addSql('DROP INDEX IDX_status_createdAt ON status');

        $this->addSql('ALTER TABLE status ADD dtmCreatedAt DATETIME NOT NULL, DROP created_at, DROP updated_at');

        $this->addSql('CREATE INDEX IDX_status_createdAt ON status (dtmCreatedAt)');

        $this->addSql('DROP INDEX IDX_task_createdAt ON task');

        $this->addSql('ALTER TABLE task CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE created_at dtmCreatedAt DATETIME NOT NULL');

        $this->addSql('CREATE INDEX IDX_task_createdAt ON task (dtmCreatedAt)');

        $this->addSql('DROP INDEX IDX_technology_createdAt ON technology');

        $this->addSql('ALTER TABLE technology ADD dtmCreatedAt DATETIME NOT NULL, DROP created_at, DROP updated_at');

        $this->addSql('CREATE INDEX IDX_technology_createdAt ON technology (dtmCreatedAt)');

        $this->addSql('ALTER TABLE tracker_history_change_log ADD dtmCreatedAt DATETIME NOT NULL, DROP created_at, DROP updated_at');

        $this->addSql('ALTER TABLE tracker_project ADD dtmCreatedAt DATETIME NOT NULL, DROP created_at, DROP updated_at');

        $this->addSql('DROP INDEX IDX_tracker_task_type_createdAt ON tracker_task_type');

        $this->addSql('ALTER TABLE tracker_task_type ADD dtmCreatedAt DATETIME NOT NULL, DROP created_at, DROP updated_at');

        $this->addSql('CREATE INDEX IDX_tracker_task_type_createdAt ON tracker_task_type (dtmCreatedAt)');

        $this->addSql('ALTER TABLE witcher_project ADD dtmCreatedAt DATETIME NOT NULL, DROP created_at, DROP updated_at');

        $this->addSql('ALTER TABLE witcher_project_tracker_task_type ADD dtmCreatedAt DATETIME NOT NULL, DROP created_at, DROP updated_at');

        $this->addSql('DROP INDEX IDX_witcher_user_createdAt ON witcher_user');

        $this->addSql('ALTER TABLE witcher_user ADD dtmCreatedAt DATETIME NOT NULL, DROP created_at, DROP updated_at');

        $this->addSql('CREATE INDEX IDX_witcher_user_createdAt ON witcher_user (dtmCreatedAt)');
    }
}
