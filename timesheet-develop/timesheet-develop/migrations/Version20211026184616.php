<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211026184616 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create required indexes for the entity Industry';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX IDX_ublIndustry_intDisplayOrder ON ublIndustry (intDisplayOrder)');
        $this->addSql('CREATE INDEX IDX_ublIndustry_dtmCreatedAt ON ublIndustry (dtmCreatedAt)');
        $this->addSql('CREATE INDEX IDX_ublIndustry_bolActive ON ublIndustry (bolActive)');
        $this->addSql('CREATE UNIQUE INDEX UK_ublIndustry_strHandle ON ublIndustry (strHandle)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_ublIndustry_intDisplayOrder ON ublIndustry');
        $this->addSql('DROP INDEX IDX_ublIndustry_dtmCreatedAt ON ublIndustry');
        $this->addSql('DROP INDEX IDX_ublIndustry_bolActive ON ublIndustry');
        $this->addSql('DROP INDEX UK_ublIndustry_strHandle ON ublIndustry');
    }
}
