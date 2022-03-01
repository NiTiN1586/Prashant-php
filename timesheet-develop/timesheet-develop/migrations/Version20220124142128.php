<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Jagaad\WitcherApi\Entity\Role;
use Jagaad\WitcherApi\Security\Enum\Permission\Activity;
use Jagaad\WitcherApi\Security\Enum\Permission\Project;
use Jagaad\WitcherApi\Security\Enum\Permission\Task;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220124142128 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Alter permission to permission_group relation, add default permissions to roles WITCHER-338';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE permission CHANGE permission_group permission_group INT NOT NULL');
        $this->addSql('ALTER TABLE permission ADD CONSTRAINT FK_E04992AABB4729B6 FOREIGN KEY (permission_group) REFERENCES permission_group (id)');
        $this->addSql('CREATE INDEX IDX_permission_permissionGroup ON permission (permission_group)');
    }

    public function postUp(Schema $schema): void
    {
        $this->connection->executeQuery(
            <<<SQL
                INSERT IGNORE INTO role_permission(role, permission)
                SELECT r.id AS role, p.id AS permission FROM role AS r, permission AS p
                WHERE r.handle = :role AND p.handle IN(:permissions)
            SQL,
            [
                'role' => Role::DEVELOPER,
                'permissions' => [
                    'VIEW_ASSIGNED_PROJECTS',
                    'VIEW_ASSIGNED_TASKS',
                    'UPDATE_ASSIGNED_TASKS',
                    'CREATE_TASKS',
                    'DELETE_ASSIGNED_TASKS',
                    'MANAGE_OWN_ACTIVITIES',
                ],
            ],
            [
                'role' => \PDO::PARAM_STR,
                'permissions' => Connection::PARAM_STR_ARRAY,
            ]
        );

        $this->connection->executeQuery(
            <<<SQL
                INSERT IGNORE INTO role_permission(role, permission)
                SELECT r.id AS role, p.id AS permission FROM role AS r, permission AS p
                WHERE r.handle = :role AND p.handle IN(:permissions)
            SQL,
            [
                'role' => Role::MANAGER,
                'permissions' => [
                    'VIEW_ASSIGNED_PROJECTS',
                    'CREATE_PROJECTS',
                    'UPDATE_ASSIGNED_PROJECTS',
                    'DELETE_ASSIGNED_PROJECTS',
                    'VIEW_ALL_TASKS',
                    'CREATE_TASKS',
                    'UPDATE_ALL_TASKS',
                    'DELETE_ALL_TASKS',
                    'MANAGE_ALL_ACTIVITIES',

                ],
            ],
            [
                'role' => \PDO::PARAM_STR,
                'permissions' => Connection::PARAM_STR_ARRAY,
            ]
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE permission DROP FOREIGN KEY FK_E04992AABB4729B6');
        $this->addSql('DROP INDEX IDX_permission_permissionGroup ON permission');
        $this->addSql('ALTER TABLE permission CHANGE permission_group permission_group INT DEFAULT NULL');
    }
}
