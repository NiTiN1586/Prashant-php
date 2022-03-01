<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220201114839 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add new activity_type values WITCHER-349';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

    }

    public function postUp(Schema $schema): void
    {
        $query = <<<SQL
            INSERT IGNORE INTO activity_type(friendly_name, handle, display_order, created_at, updated_at) VALUES
                ('Analysis', 'ANALYSIS', 7, NOW(), NOW()),
                ('1 to 1 meeting', '1_TO_1_MEETING', 8, NOW(), NOW()),
                ('Off', 'OFF', 9, NOW(), NOW()),
                ('Management', 'MANAGEMENT', 10, NOW(), NOW()),
                ('Public Holidays', 'PUBLIC_HOLIDAYS', 11, NOW(), NOW()),
                ('Sick', 'SICK', 12, NOW(), NOW()),
                ('Research', 'RESEARCH', 13, NOW(), NOW()), 
                ('QA Testing', 'QA_TESTING', 14, NOW(), NOW()),
                ('Technical Interview', 'INTERVIEW', 15, NOW(), NOW());
            SQL;

        $this->connection->executeQuery($query);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
