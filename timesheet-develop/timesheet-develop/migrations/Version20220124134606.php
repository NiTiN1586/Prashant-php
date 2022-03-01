<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220124134606 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create permission_group WITCHER-338';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE permission_group (id INT AUTO_INCREMENT NOT NULL, handle VARCHAR(50) NOT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(200) NOT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_permission_group_createdAt (created_at), INDEX IDX_permission_group_updatedAt (updated_at), INDEX IDX_permission_group_deletedAt (deleted_at), UNIQUE INDEX UK_permission_group_handle (handle), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE permission ADD permission_group INT DEFAULT NULL');
    }

    public function postUp(Schema $schema): void
    {
        $this->connection->executeQuery(
            <<<SQL
            INSERT IGNORE INTO permission_group(name, handle, description, created_at, updated_at) VALUES
                ('System', 'SYSTEM', 'System permission group', NOW(), NOW()),
                ('Tasks', 'TASKS', 'Task-related permission group', NOW(), NOW()),
                ('Projects', 'PROJECTS', 'Project-related permission group', NOW(), NOW()),
                ('Activities', 'ACTIVITIES', 'Activity-related permission group', NOW(), NOW())
            SQL
        );

        $this->connection->executeQuery(
            <<<SQL
            UPDATE permission p SET p.permission_group = (SELECT id FROM permission_group pg WHERE pg.handle = 'SYSTEM')
                WHERE p.handle = 'MANAGE_ROLES'
            SQL
        );

        $this->connection->executeQuery(
            <<<SQL
            UPDATE permission p SET p.permission_group = (SELECT id FROM permission_group pg WHERE pg.handle = 'TASKS')
                WHERE p.handle LIKE '%_TASKS'
            SQL
        );

        $this->connection->executeQuery(
            <<<SQL
            UPDATE permission p SET p.permission_group = (SELECT id FROM permission_group pg WHERE pg.handle = 'PROJECTS')
                WHERE p.handle LIKE '%_PROJECTS'
            SQL
        );

        $this->connection->executeQuery(
            <<<SQL
            UPDATE permission p SET p.permission_group = (SELECT id FROM permission_group pg WHERE pg.handle = 'ACTIVITIES')
                WHERE p.handle LIKE '%_ACTIVITIES' OR p.handle = 'MANAGE_ACTIVITY_TYPE'
            SQL
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE permission DROP FOREIGN KEY FK_E04992AABB4729B6');
        $this->addSql('DROP TABLE permission_group');
        $this->addSql('DROP INDEX IDX_permission_permissionGroup ON permission');
        $this->addSql('ALTER TABLE permission DROP permission_group');
    }
}
