<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211026182209 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create required indexes for the entity Country';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX IDX_ublCountry_intDisplayOrder ON ublCountry (intDisplayOrder)');
        $this->addSql('CREATE INDEX IDX_ublCountry_bolActive ON ublCountry (bolActive)');
        $this->addSql('CREATE INDEX IDX_ublCountry_dtmCreatedAt ON ublCountry (dtmCreatedAt)');
        $this->addSql('CREATE UNIQUE INDEX UK_ublCountry_strHandle ON ublCountry (strHandle)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_ublCountry_intDisplayOrder ON ublCountry');
        $this->addSql('DROP INDEX IDX_ublCountry_bolActive ON ublCountry');
        $this->addSql('DROP INDEX IDX_ublCountry_dtmCreatedAt ON ublCountry');
        $this->addSql('DROP INDEX UK_ublCountry_strHandle ON ublCountry');
    }
}
