<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211026181753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create required indexes for the entity BusinessBranch';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX IDX_ublBusinessBranch_intDisplayOrder ON ublBusinessBranch (intDisplayOrder)');
        $this->addSql('CREATE INDEX IDX_ublBusinessBranch_dtmCreatedAt ON ublBusinessBranch (dtmCreatedAt)');
        $this->addSql('CREATE INDEX IDX_ublBusinessBranch_bolActive ON ublBusinessBranch (bolActive)');
        $this->addSql('CREATE UNIQUE INDEX UK_ublBusinessBranch_strHandle ON ublBusinessBranch (strHandle)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_ublBusinessBranch_intDisplayOrder ON ublBusinessBranch');
        $this->addSql('DROP INDEX IDX_ublBusinessBranch_dtmCreatedAt ON ublBusinessBranch');
        $this->addSql('DROP INDEX IDX_ublBusinessBranch_bolActive ON ublBusinessBranch');
        $this->addSql('DROP INDEX UK_ublBusinessBranch_strHandle ON ublBusinessBranch');
    }
}
