<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211214150622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Make git_lab_user_id nullable WITCHER-253';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE witcher_user CHANGE git_lab_user_id git_lab_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE witcher_user CHANGE company_position company_position INT DEFAULT NULL');
    }

    public function postUp(Schema $schema): void
    {
        $query = <<<SQL
            INSERT IGNORE INTO company_position(name, handle, created_at, updated_at) VALUES
                ('Employee', 'EMPLOYEE', NOW(), NOW());
            SQL;

        $this->connection->executeQuery($query);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE witcher_user CHANGE git_lab_user_id git_lab_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE witcher_user CHANGE company_position company_position INT NOT NULL');
    }
}
