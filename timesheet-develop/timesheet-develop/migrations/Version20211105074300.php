<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211105074300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add deleted_at column to department';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE department ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_department_deletedAt ON department (deleted_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_department_deletedAt ON department');
        $this->addSql('ALTER TABLE department DROP deleted_at');
    }
}
