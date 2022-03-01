<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211101104226 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Made entity ProjectSaleType soft-deleteable';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ublProjectSaleType ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_ublProjectSaleType_deletedAt ON ublProjectSaleType (deleted_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_ublProjectSaleType_deletedAt ON ublProjectSaleType');
        $this->addSql('ALTER TABLE ublProjectSaleType DROP deleted_at');
    }
}
