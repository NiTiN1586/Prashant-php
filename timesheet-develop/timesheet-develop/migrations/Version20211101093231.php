<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211101093231 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Made entity Currency soft-deleteable';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ublCurrency ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_ublCurrency_deletedAt ON ublCurrency (deleted_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_ublCurrency_deletedAt ON ublCurrency');
        $this->addSql('ALTER TABLE ublCurrency DROP deleted_at');
    }
}
