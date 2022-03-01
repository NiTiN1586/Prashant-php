<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211026182348 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create required indexes for the entity Currency';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX IDX_ublCurrency_intDisplayOrder ON ublCurrency (intDisplayOrder)');
        $this->addSql('CREATE INDEX IDX_ublCurrency_dtmCreatedAt ON ublCurrency (dtmCreatedAt)');
        $this->addSql('CREATE INDEX IDX_ublCurrency_bolActive ON ublCurrency (bolActive)');
        $this->addSql('CREATE UNIQUE INDEX UK_ublCurrency_strHandle ON ublCurrency (strHandle)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_ublCurrency_intDisplayOrder ON ublCurrency');
        $this->addSql('DROP INDEX IDX_ublCurrency_dtmCreatedAt ON ublCurrency');
        $this->addSql('DROP INDEX IDX_ublCurrency_bolActive ON ublCurrency');
        $this->addSql('DROP INDEX UK_ublCurrency_strHandle ON ublCurrency');
    }
}
