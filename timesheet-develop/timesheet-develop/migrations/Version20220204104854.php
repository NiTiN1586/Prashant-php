<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220204104854 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update activity permissions WITCHER-343';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

    }

    public function postUp(Schema $schema): void
    {
        $formattedPermission = [];

        $permissions = $this->connection->executeQuery(
            <<<SQL
                SELECT handle, id FROM permission
                WHERE handle IN('MANAGE_OWN_ACTIVITIES', 'MANAGE_ALL_ACTIVITIES', 'VIEW_ALL_ACTIVITIES')
            SQL
        );

        foreach ($permissions as $permission) {
            $formattedPermission[$permission['handle']] = $permission['id'];
        }

        $this->connection->executeQuery(
            <<<SQL
            UPDATE role_permission SET permission = :manageOwnActivity
            WHERE permission IN(:allPermissions);
            SQL,
            [
                'manageOwnActivity' => $formattedPermission['MANAGE_OWN_ACTIVITIES'],
                'allPermissions' => \array_values($formattedPermission),
            ],
            [
                'manageOwnActivity' => \PDO::PARAM_INT,
                'allPermissions' => Connection::PARAM_INT_ARRAY,
            ]
        );

        $this->connection->executeQuery(
            <<<SQL
                DELETE FROM permission WHERE handle IN('MANAGE_ALL_ACTIVITIES', 'VIEW_ALL_ACTIVITIES')
            SQL
        );

        $this->connection->executeQuery(
            <<<SQL
            UPDATE permission SET
              handle = 'MANAGE_ACTIVITIES',
              name = 'Manage activities',
              description = 'Allows to create/view/edit/delete activity'
            WHERE handle = 'MANAGE_OWN_ACTIVITIES'
            SQL
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
