<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211210154024 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create jira account owner relation in tracker_project';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tracker_project ADD project_owner INT NOT NULL');
        $this->addSql('ALTER TABLE tracker_project ADD CONSTRAINT FK_78ECAD02DC35EFE2 FOREIGN KEY (project_owner) REFERENCES tracker_account (id)');
        $this->addSql('CREATE INDEX IDX_tracker_project_projectOwner ON tracker_project (project_owner)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tracker_project DROP FOREIGN KEY FK_78ECAD02DC35EFE2');
        $this->addSql('DROP INDEX IDX_tracker_project_projectOwner ON tracker_project');
        $this->addSql('ALTER TABLE tracker_project DROP project_owner');
    }
}
