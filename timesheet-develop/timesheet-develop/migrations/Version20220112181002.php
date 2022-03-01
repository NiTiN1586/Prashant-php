<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220112181002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add updated_by, created_by user WITCHER-277';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tracker_project DROP FOREIGN KEY FK_78ECAD02DC35EFE2');
        $this->addSql('DROP INDEX IDX_tracker_project_projectOwner ON tracker_project');
        $this->addSql('ALTER TABLE tracker_project ADD updated_by INT DEFAULT NULL, CHANGE project_owner created_by INT NOT NULL');
        $this->addSql('ALTER TABLE tracker_project ADD CONSTRAINT FK_78ECAD02DE12AB56 FOREIGN KEY (created_by) REFERENCES witcher_user (id)');

        $this->addSql('ALTER TABLE tracker_project ADD CONSTRAINT FK_78ECAD0216FE72E1 FOREIGN KEY (updated_by) REFERENCES witcher_user (id)');
        $this->addSql('CREATE INDEX IDX_tracker_project_createdBy ON tracker_project (created_by)');
        $this->addSql('CREATE INDEX IDX_tracker_project_updatedBy ON tracker_project (updated_by)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tracker_project DROP FOREIGN KEY FK_78ECAD02DE12AB56');
        $this->addSql('ALTER TABLE tracker_project DROP FOREIGN KEY FK_78ECAD0216FE72E1');
        $this->addSql('DROP INDEX IDX_tracker_project_createdBy ON tracker_project');
        $this->addSql('DROP INDEX IDX_tracker_project_updatedBy ON tracker_project');

        $this->addSql('ALTER TABLE tracker_project DROP updated_by, CHANGE created_by project_owner INT NOT NULL');
        $this->addSql('ALTER TABLE tracker_project ADD CONSTRAINT FK_78ECAD02DC35EFE2 FOREIGN KEY (project_owner) REFERENCES tracker_account (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_tracker_project_projectOwner ON tracker_project (project_owner)');
    }
}
