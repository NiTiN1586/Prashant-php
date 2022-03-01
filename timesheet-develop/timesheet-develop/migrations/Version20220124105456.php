<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220124105456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Amend structure of witcher project WITCHER-337';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE witcher_project DROP FOREIGN KEY FK_70D1B33278ECAD02');
        $this->addSql('DROP TABLE tracker_project');
        $this->addSql('DROP INDEX UK_witcher_project_trackerProject ON witcher_project');
        $this->addSql('ALTER TABLE witcher_project ADD external_tracker_link VARCHAR(255) DEFAULT NULL, ADD external_key VARCHAR(20) DEFAULT NULL, DROP tracker_project');
        $this->addSql('CREATE UNIQUE INDEX UK_witcher_project_externalKey ON witcher_project (external_key)');
        $this->addSql('ALTER TABLE task ADD external_tracker_link VARCHAR(255) DEFAULT NULL, DROP jira_link');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tracker_project (id INT AUTO_INCREMENT NOT NULL, created_by INT NOT NULL, updated_by INT DEFAULT NULL, jira_project_id INT NOT NULL, created_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, jira_project_link VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, updated_at DATETIME NOT NULL, INDEX IDX_tracker_project_createdBy (created_by), INDEX IDX_tracker_project_deletedAt (deleted_at), INDEX IDX_tracker_project_updatedBy (updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE tracker_project ADD CONSTRAINT FK_78ECAD0216FE72E1 FOREIGN KEY (updated_by) REFERENCES witcher_user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE tracker_project ADD CONSTRAINT FK_78ECAD02DE12AB56 FOREIGN KEY (created_by) REFERENCES witcher_user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP INDEX UK_witcher_project_externalKey ON witcher_project');
        $this->addSql('ALTER TABLE witcher_project ADD tracker_project INT DEFAULT NULL, DROP external_tracker_link, DROP external_key');
        $this->addSql('ALTER TABLE witcher_project ADD CONSTRAINT FK_70D1B33278ECAD02 FOREIGN KEY (tracker_project) REFERENCES tracker_project (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UK_witcher_project_trackerProject ON witcher_project (tracker_project)');
        $this->addSql('ALTER TABLE task ADD jira_link VARCHAR(200) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP external_tracker_link');
    }
}
