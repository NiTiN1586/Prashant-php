<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211027072312 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create required indexes for the entity Priority';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX IDX_ublPriority_intDisplayOrder ON ublPriority (intDisplayOrder)');
        $this->addSql('CREATE INDEX IDX_ublPriority_dtmCreatedAt ON ublPriority (dtmCreatedAt)');
        $this->addSql('CREATE INDEX IDX_ublPriority_bolActive ON ublPriority (bolActive)');
        $this->addSql('ALTER TABLE ublPriority RENAME INDEX uk_ublpriority_handle TO UK_ublPriority_strHandle');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_ublPriority_intDisplayOrder ON ublPriority');
        $this->addSql('DROP INDEX IDX_ublPriority_dtmCreatedAt ON ublPriority');
        $this->addSql('DROP INDEX IDX_ublPriority_bolActive ON ublPriority');
        $this->addSql('ALTER TABLE ublPriority RENAME INDEX uk_ublpriority_strhandle TO UK_ublPriority_handle');
    }
}
