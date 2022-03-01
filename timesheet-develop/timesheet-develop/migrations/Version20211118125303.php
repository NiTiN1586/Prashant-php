<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211118125303 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove EntityActivityTrait because we decided to use soft deletion that adds deleted_at column as per ticket WITCHER-194.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_activity_active ON activity');

        $this->addSql('ALTER TABLE activity DROP active');

        $this->addSql('DROP INDEX IDX_activity_type_active ON activity_type');

        $this->addSql('ALTER TABLE activity_type DROP active');

        $this->addSql('DROP INDEX IDX_business_branch_active ON business_branch');

        $this->addSql('ALTER TABLE business_branch DROP active');

        $this->addSql('DROP INDEX IDX_client_active ON client');

        $this->addSql('ALTER TABLE client DROP active');

        $this->addSql('DROP INDEX IDX_country_active ON country');

        $this->addSql('ALTER TABLE country DROP active');

        $this->addSql('DROP INDEX IDX_currency_active ON currency');

        $this->addSql('ALTER TABLE currency DROP active');

        $this->addSql('DROP INDEX IDX_git_project_active ON git_project');

        $this->addSql('ALTER TABLE git_project DROP active');

        $this->addSql('DROP INDEX IDX_industry_active ON industry');

        $this->addSql('ALTER TABLE industry DROP active');

        $this->addSql('DROP INDEX IDX_priority_active ON priority');

        $this->addSql('ALTER TABLE priority DROP active');

        $this->addSql('DROP INDEX IDX_project_sale_type_active ON project_sale_type');

        $this->addSql('ALTER TABLE project_sale_type DROP active');

        $this->addSql('DROP INDEX IDX_status_active ON status');

        $this->addSql('ALTER TABLE status DROP active');

        $this->addSql('ALTER TABLE task DROP active');

        $this->addSql('DROP INDEX IDX_technology_active ON technology');

        $this->addSql('ALTER TABLE technology DROP active');

        $this->addSql('ALTER TABLE tracker_project DROP active');

        $this->addSql('DROP INDEX IDX_tracker_task_type_active ON tracker_task_type');

        $this->addSql('ALTER TABLE tracker_task_type DROP active');

        $this->addSql('ALTER TABLE witcher_project DROP active');

        $this->addSql('ALTER TABLE witcher_project_tracker_task_type ADD deleted_at DATETIME DEFAULT NULL, DROP active');

        $this->addSql('CREATE INDEX IDX_witcher_project_deletedAt ON witcher_project_tracker_task_type (deleted_at)');

        $this->addSql('DROP INDEX IDX_witcher_user_active ON witcher_user');

        $this->addSql('ALTER TABLE witcher_user DROP active');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity ADD active TINYINT(1) DEFAULT \'1\' NOT NULL');

        $this->addSql('CREATE INDEX IDX_activity_active ON activity (active)');

        $this->addSql('ALTER TABLE activity_type ADD active TINYINT(1) DEFAULT \'1\' NOT NULL');

        $this->addSql('CREATE INDEX IDX_activity_type_active ON activity_type (active)');

        $this->addSql('ALTER TABLE business_branch ADD active TINYINT(1) DEFAULT \'1\' NOT NULL');

        $this->addSql('CREATE INDEX IDX_business_branch_active ON business_branch (active)');

        $this->addSql('ALTER TABLE client ADD active TINYINT(1) DEFAULT \'1\' NOT NULL');

        $this->addSql('CREATE INDEX IDX_client_active ON client (active)');

        $this->addSql('ALTER TABLE country ADD active TINYINT(1) DEFAULT \'1\' NOT NULL');

        $this->addSql('CREATE INDEX IDX_country_active ON country (active)');

        $this->addSql('ALTER TABLE currency ADD active TINYINT(1) DEFAULT \'1\' NOT NULL');

        $this->addSql('CREATE INDEX IDX_currency_active ON currency (active)');

        $this->addSql('ALTER TABLE git_project ADD active TINYINT(1) DEFAULT \'1\' NOT NULL');

        $this->addSql('CREATE INDEX IDX_git_project_active ON git_project (active)');

        $this->addSql('ALTER TABLE industry ADD active TINYINT(1) DEFAULT \'1\' NOT NULL');

        $this->addSql('CREATE INDEX IDX_industry_active ON industry (active)');

        $this->addSql('ALTER TABLE priority ADD active TINYINT(1) DEFAULT \'1\' NOT NULL');

        $this->addSql('CREATE INDEX IDX_priority_active ON priority (active)');

        $this->addSql('ALTER TABLE project_sale_type ADD active TINYINT(1) DEFAULT \'1\' NOT NULL');

        $this->addSql('CREATE INDEX IDX_project_sale_type_active ON project_sale_type (active)');

        $this->addSql('ALTER TABLE status ADD active TINYINT(1) DEFAULT \'1\' NOT NULL');

        $this->addSql('CREATE INDEX IDX_status_active ON status (active)');

        $this->addSql('ALTER TABLE task ADD active TINYINT(1) DEFAULT \'1\' NOT NULL');

        $this->addSql('ALTER TABLE technology ADD active TINYINT(1) DEFAULT \'1\' NOT NULL');

        $this->addSql('CREATE INDEX IDX_technology_active ON technology (active)');

        $this->addSql('ALTER TABLE tracker_project ADD active TINYINT(1) DEFAULT \'1\' NOT NULL');

        $this->addSql('ALTER TABLE tracker_task_type ADD active TINYINT(1) DEFAULT \'1\' NOT NULL');

        $this->addSql('CREATE INDEX IDX_tracker_task_type_active ON tracker_task_type (active)');

        $this->addSql('ALTER TABLE witcher_project ADD active TINYINT(1) DEFAULT \'1\' NOT NULL');

        $this->addSql('DROP INDEX IDX_witcher_project_deletedAt ON witcher_project_tracker_task_type');

        $this->addSql('ALTER TABLE witcher_project_tracker_task_type ADD active TINYINT(1) DEFAULT \'1\' NOT NULL, DROP deleted_at');

        $this->addSql('ALTER TABLE witcher_user ADD active TINYINT(1) DEFAULT \'1\' NOT NULL');

        $this->addSql('CREATE INDEX IDX_witcher_user_active ON witcher_user (active)');
    }
}
