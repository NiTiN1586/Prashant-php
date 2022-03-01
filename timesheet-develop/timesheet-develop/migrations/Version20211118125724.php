<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211118125724 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Implement role permission tables';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE permission (id INT AUTO_INCREMENT NOT NULL, handle VARCHAR(50) NOT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(1000) NOT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_permission_createdAt (created_at), INDEX IDX_permission_updatedAt (updated_at), INDEX IDX_permission_deletedAt (deleted_at), UNIQUE INDEX UK_permission_handle (handle), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, handle VARCHAR(50) NOT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(1000) NOT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_role_createdAt (created_at), INDEX IDX_role_deletedAt (deleted_at), INDEX IDX_role_updatedAt (updated_at), UNIQUE INDEX UK_role_handle (handle), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role_permission (role INT NOT NULL, permission INT NOT NULL, INDEX IDX_6F7DF88657698A6A (role), INDEX IDX_6F7DF886E04992AA (permission), PRIMARY KEY(role, permission)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE role_permission ADD CONSTRAINT FK_6F7DF88657698A6A FOREIGN KEY (role) REFERENCES role (id)');
        $this->addSql('ALTER TABLE role_permission ADD CONSTRAINT FK_6F7DF886E04992AA FOREIGN KEY (permission) REFERENCES permission (id)');
        $this->addSql('ALTER TABLE witcher_user ADD role INT NOT NULL, DROP witcher_roles');
        $this->addSql('ALTER TABLE witcher_user ADD CONSTRAINT FK_102CBBE357698A6A FOREIGN KEY (role) REFERENCES role (id)');
        $this->addSql('CREATE INDEX IDX_witcher_user_role ON witcher_user (role)');
    }

    public function postUp(Schema $schema): void
    {
        // Populates role table
        $this->connection->executeQuery("INSERT IGNORE INTO role (handle, name, description, created_at, updated_at) VALUES ('ROLE_ADMIN', 'Admin', 'Administrator Role', NOW(), NOW())");
        $this->connection->executeQuery("INSERT IGNORE INTO role (handle, name, description, created_at, updated_at) VALUES ('ROLE_MANAGER', 'Manager', 'Manager Role', NOW(), NOW())");
        $this->connection->executeQuery("INSERT IGNORE INTO role (handle, name, description, created_at, updated_at) VALUES ('ROLE_DEVELOPER', 'Developer', 'Developer Role', NOW(), NOW())");

        // Populates permission
        $this->connection->executeQuery("INSERT IGNORE INTO permission (handle, name, description, created_at, updated_at) VALUES('VIEW_ASSIGNED_TASKS', 'View assigned tasks', 'Allows to view assigned to user tasks', NOW(), NOW())");
        $this->connection->executeQuery("INSERT IGNORE INTO permission (handle, name, description, created_at, updated_at) VALUES('VIEW_ALL_TASKS', 'View all tasks', 'Allows to view all tasks', NOW(), NOW())");
        $this->connection->executeQuery("INSERT IGNORE INTO permission (handle, name, description, created_at, updated_at) VALUES('VIEW_ASSIGNED_PROJECTS', 'View assigned projects', 'Allows to view assigned to user projects', NOW(), NOW())");
        $this->connection->executeQuery("INSERT IGNORE INTO permission (handle, name, description, created_at, updated_at) VALUES('VIEW_ALL_PROJECTS', 'View all projects', 'Allows to view all projects', NOW(), NOW())");

        $this->connection->executeQuery("INSERT IGNORE INTO permission (handle, name, description, created_at, updated_at) VALUES('CREATE_TASKS', 'Create tasks', 'Allows to create  tasks', NOW(), NOW())");
        $this->connection->executeQuery("INSERT IGNORE INTO permission (handle, name, description, created_at, updated_at) VALUES('CREATE_PROJECTS', 'Create projects', 'Allows to create projects', NOW(), NOW())");

        $this->connection->executeQuery("INSERT IGNORE INTO permission (handle, name, description, created_at, updated_at) VALUES('UPDATE_ASSIGNED_TASKS', 'Update assigned tasks', 'Allows to update assigned to user tasks', NOW(), NOW())");
        $this->connection->executeQuery("INSERT IGNORE INTO permission (handle, name, description, created_at, updated_at) VALUES('UPDATE_ALL_TASKS', 'Update all tasks', 'Allows to update all tasks', NOW(), NOW())");
        $this->connection->executeQuery("INSERT IGNORE INTO permission (handle, name, description, created_at, updated_at) VALUES('UPDATE_ASSIGNED_PROJECTS', 'Update assigned projects', 'Allows to update assigned to user projects', NOW(), NOW())");
        $this->connection->executeQuery("INSERT IGNORE INTO permission (handle, name, description, created_at, updated_at) VALUES('UPDATE_ALL_PROJECTS', 'Update all projects', 'Allows to update all projects', NOW(), NOW())");

        $this->connection->executeQuery("INSERT IGNORE INTO permission (handle, name, description, created_at, updated_at) VALUES('DELETE_ASSIGNED_TASKS', 'Delete assigned tasks', 'Allows to delete assigned tasks', NOW(), NOW())");
        $this->connection->executeQuery("INSERT IGNORE INTO permission (handle, name, description, created_at, updated_at) VALUES('DELETE_ALL_TASKS', 'Delete all tasks', 'Allows to delete all tasks', NOW(), NOW())");

        $this->connection->executeQuery("INSERT IGNORE INTO permission (handle, name, description, created_at, updated_at) VALUES('DELETE_ASSIGNED_PROJECTS', 'Delete assigned projects', 'Allows to delete assigned projects', NOW(), NOW())");
        $this->connection->executeQuery("INSERT IGNORE INTO permission (handle, name, description, created_at, updated_at) VALUES('DELETE_ALL_PROJECTS', 'Delete all projects', 'Allows to delete all projects', NOW(), NOW())");

        $this->connection->executeQuery("INSERT IGNORE INTO permission (handle, name, description, created_at, updated_at) VALUES('MANAGE_ROLES', 'Manage roles', 'Allows to view, create, edit and delete roles and permissions', NOW(), NOW())");
        $this->connection->executeQuery("INSERT IGNORE INTO permission (handle, name, description, created_at, updated_at) VALUES('VIEW_ROLES', 'View roles', 'Allows to view roles and permissions', NOW(), NOW())");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE role_permission DROP FOREIGN KEY FK_6F7DF886E04992AA');
        $this->addSql('ALTER TABLE role_permission DROP FOREIGN KEY FK_6F7DF88657698A6A');
        $this->addSql('ALTER TABLE witcher_user DROP FOREIGN KEY FK_102CBBE357698A6A');
        $this->addSql('DROP TABLE permission');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE role_permission');
        $this->addSql('DROP INDEX IDX_witcher_user_role ON witcher_user');
        $this->addSql('ALTER TABLE witcher_user ADD witcher_roles JSON NOT NULL, DROP role');
    }
}
