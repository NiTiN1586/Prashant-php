<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220114133211 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Implement roles and permissions functionality WITCHER-152';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

    }

    public function postUp(Schema $schema): void
    {
        $this->connection->executeQuery(
            <<<SQL
            INSERT IGNORE INTO permission(name, handle, description, created_at, updated_at) VALUES
                ('Manage own activities', 'MANAGE_OWN_ACTIVITIES', 'Allows to create/view/edit/delete own activity', NOW(), NOW()),
                ('Manage all activities', 'MANAGE_ALL_ACTIVITIES', 'Allows to create/view/edit/delete any activity', NOW(), NOW()),
                ('View activities', 'VIEW_ALL_ACTIVITIES', 'Allows to view any activity', NOW(), NOW());
            SQL
        );

        $this->connection->executeQuery(
            <<<SQL
                DELETE IGNORE FROM permission WHERE handle = 'VIEW_ROLES';
            SQL
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
