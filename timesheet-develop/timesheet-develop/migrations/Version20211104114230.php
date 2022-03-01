<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211104114230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Amend activity_type';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity_type CHANGE dtmCreatedAt dtmCreatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE tracker_task_type CHANGE dtmCreatedAt dtmCreatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE witcher_project_tracker_task_type CHANGE dtmCreatedAt dtmCreatedAt DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity_type CHANGE dtmCreatedAt dtmCreatedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE tracker_task_type CHANGE dtmCreatedAt dtmCreatedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE witcher_project_tracker_task_type CHANGE dtmCreatedAt dtmCreatedAt DATETIME DEFAULT NULL');
    }
}
