<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210929140826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Amend unique key for tblWitcherProject';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblWitcherProjectActivityType ADD INDEX idx_intActivityTypeId (intActivityTypeId)');
        $this->addSql('ALTER TABLE tblWitcherProjectActivityType ADD UNIQUE INDEX UNIQ_650105483281966B5AE2AC1B (intWitcherProjectId, intActivityTypeId)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblWitcherProjectActivityType ADD UNIQUE INDEX UNIQ_650105485AE2AC1B (intActivityTypeId)');
        $this->addSql('ALTER TABLE tblWitcherProjectActivityType ADD INDEX idx_intWitcherProjectId_intActivityTypeId (intWitcherProjectId, intActivityTypeId)');
    }
}
