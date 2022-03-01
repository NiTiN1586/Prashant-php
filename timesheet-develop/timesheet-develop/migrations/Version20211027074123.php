<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211027074123 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create required indexes for the entity ProjectSaleType';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX IDX_ublProjectSaleType_intDisplayOrder ON ublProjectSaleType (intDisplayOrder)');
        $this->addSql('CREATE INDEX IDX_ublProjectSaleType_dtmCreatedAt ON ublProjectSaleType (dtmCreatedAt)');
        $this->addSql('CREATE INDEX IDX_ublProjectSaleType_bolActive ON ublProjectSaleType (bolActive)');
        $this->addSql('CREATE UNIQUE INDEX UK_ublProjectSaleType_strHandle ON ublProjectSaleType (strHandle)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_ublProjectSaleType_intDisplayOrder ON ublProjectSaleType');
        $this->addSql('DROP INDEX IDX_ublProjectSaleType_dtmCreatedAt ON ublProjectSaleType');
        $this->addSql('DROP INDEX IDX_ublProjectSaleType_bolActive ON ublProjectSaleType');
        $this->addSql('DROP INDEX UK_ublProjectSaleType_strHandle ON ublProjectSaleType');
    }
}
