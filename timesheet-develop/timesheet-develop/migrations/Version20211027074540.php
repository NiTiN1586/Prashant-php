<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211027074540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create required indexes for the entity Technology';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX IDX_ublTechnology_intDisplayOrder ON ublTechnology (intDisplayOrder)');
        $this->addSql('CREATE INDEX IDX_ublTechnology_dtmCreatedAt ON ublTechnology (dtmCreatedAt)');
        $this->addSql('CREATE INDEX IDX_ublTechnology_bolActive ON ublTechnology (bolActive)');
        $this->addSql('CREATE UNIQUE INDEX UK_ublTechnology_strHandle ON ublTechnology (strHandle)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_ublTechnology_intDisplayOrder ON ublTechnology');
        $this->addSql('DROP INDEX IDX_ublTechnology_dtmCreatedAt ON ublTechnology');
        $this->addSql('DROP INDEX IDX_ublTechnology_bolActive ON ublTechnology');
        $this->addSql('DROP INDEX UK_ublTechnology_strHandle ON ublTechnology');
    }
}
