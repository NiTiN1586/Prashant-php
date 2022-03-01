<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210927100057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add unique index and default values for ublActivityType';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblTask CHANGE intPriorityId intPriorityId INT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UK_ublActivityType_handle ON ublActivityType (strHandle)');
        $this->addSql('CREATE UNIQUE INDEX UK_ublPriority_handle ON ublPriority (strHandle)');
        $this->addSql('CREATE UNIQUE INDEX UK_ublStatus_handle ON ublStatus (strHandle)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblTask CHANGE intPriorityId intPriorityId INT NOT NULL');
        $this->addSql('DROP INDEX UK_ublActivityType_handle ON ublActivityType');
        $this->addSql('DROP INDEX UK_ublPriority_handle ON ublPriority');
        $this->addSql('DROP INDEX UK_ublStatus_handle ON ublStatus');
    }
}
