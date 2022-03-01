<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211213085527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add slug to witcher_project';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tracker_project DROP jira_project_key');
        $this->addSql('ALTER TABLE witcher_project ADD slug VARCHAR(20) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UK_witcher_project_handle ON witcher_project (slug)');
        $this->addSql('ALTER TABLE witcher_project RENAME INDEX uk_witcher_project_handle TO UK_witcher_project_slug');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tracker_project ADD jira_project_key VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('DROP INDEX UK_witcher_project_handle ON witcher_project');
        $this->addSql('ALTER TABLE witcher_project DROP slug');
        $this->addSql('ALTER TABLE witcher_project RENAME INDEX uk_witcher_project_slug TO UK_witcher_project_handle');
    }
}
