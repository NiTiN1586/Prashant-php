<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211027074249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create required indexes for the entity Status';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX IDX_ublStatus_intDisplayOrder ON ublStatus (intDisplayOrder)');
        $this->addSql('CREATE INDEX IDX_ublStatus_dtmCreatedAt ON ublStatus (dtmCreatedAt)');
        $this->addSql('CREATE INDEX IDX_ublStatus_bolActive ON ublStatus (bolActive)');
        $this->addSql('ALTER TABLE ublStatus RENAME INDEX uk_ublstatus_handle TO UK_ublStatus_strHandle');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_ublStatus_intDisplayOrder ON ublStatus');
        $this->addSql('DROP INDEX IDX_ublStatus_dtmCreatedAt ON ublStatus');
        $this->addSql('DROP INDEX IDX_ublStatus_bolActive ON ublStatus');
        $this->addSql('ALTER TABLE ublStatus RENAME INDEX uk_ublstatus_strhandle TO UK_ublStatus_handle');
    }
}
