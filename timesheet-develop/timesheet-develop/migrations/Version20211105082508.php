<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211105082508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add deleted_at column to company_position';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company_position ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_company_position_deletedAt ON company_position (deleted_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_company_position_deletedAt ON company_position');
        $this->addSql('ALTER TABLE company_position DROP deleted_at');
    }
}
