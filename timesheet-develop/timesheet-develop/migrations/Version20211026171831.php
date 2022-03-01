<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211026171831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create required indexes for the entity ActivityType';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX IDX_ublActivityType_intDisplayOrder ON ublActivityType (intDisplayOrder)');
        $this->addSql('CREATE INDEX IDX_ublActivityType_dtmCreatedAt ON ublActivityType (dtmCreatedAt)');
        $this->addSql('CREATE INDEX IDX_ublActivityType_bolActive ON ublActivityType (bolActive)');
        $this->addSql('ALTER TABLE ublActivityType RENAME INDEX uk_ublactivitytype_handle TO UK_ublActivityType_strHandle');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_ublActivityType_intDisplayOrder ON ublActivityType');
        $this->addSql('DROP INDEX IDX_ublActivityType_dtmCreatedAt ON ublActivityType');
        $this->addSql('DROP INDEX IDX_ublActivityType_bolActive ON ublActivityType');
        $this->addSql('ALTER TABLE ublActivityType RENAME INDEX uk_ublactivitytype_strhandle TO UK_ublActivityType_handle');
    }
}
