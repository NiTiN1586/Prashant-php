<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211102133429 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change dtmCreatedAt to NOT NULL';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company_position CHANGE dtmCreatedAt dtmCreatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE department CHANGE dtmCreatedAt dtmCreatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE tblActivity CHANGE dtmCreatedAt dtmCreatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE tblClient CHANGE dtmCreatedAt dtmCreatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE tblGitBranch CHANGE dtmCreatedAt dtmCreatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE tblGitLabProject CHANGE dtmCreatedAt dtmCreatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE tblHistoryChangeLog CHANGE dtmCreatedAt dtmCreatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE tblJiraProject CHANGE dtmCreatedAt dtmCreatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE tblTask CHANGE dtmCreatedAt dtmCreatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE tblWitcherProject CHANGE dtmCreatedAt dtmCreatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE tblWitcherProjectActivityType CHANGE dtmCreatedAt dtmCreatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE tblWitcherUser CHANGE dtmCreatedAt dtmCreatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE ublActivityType CHANGE dtmCreatedAt dtmCreatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE ublBusinessBranch CHANGE dtmCreatedAt dtmCreatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE ublCountry CHANGE dtmCreatedAt dtmCreatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE ublCurrency CHANGE dtmCreatedAt dtmCreatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE ublIndustry CHANGE dtmCreatedAt dtmCreatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE ublPriority CHANGE dtmCreatedAt dtmCreatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE ublProjectSaleType CHANGE dtmCreatedAt dtmCreatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE ublStatus CHANGE dtmCreatedAt dtmCreatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE ublTechnology CHANGE dtmCreatedAt dtmCreatedAt DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company_position CHANGE dtmCreatedAt dtmCreatedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE department CHANGE dtmCreatedAt dtmCreatedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE tblActivity CHANGE dtmCreatedAt dtmCreatedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE tblClient CHANGE dtmCreatedAt dtmCreatedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE tblGitBranch CHANGE dtmCreatedAt dtmCreatedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE tblGitLabProject CHANGE dtmCreatedAt dtmCreatedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE tblHistoryChangeLog CHANGE dtmCreatedAt dtmCreatedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE tblJiraProject CHANGE dtmCreatedAt dtmCreatedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE tblTask CHANGE dtmCreatedAt dtmCreatedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE tblWitcherProject CHANGE dtmCreatedAt dtmCreatedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE tblWitcherProjectActivityType CHANGE dtmCreatedAt dtmCreatedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE tblWitcherUser CHANGE dtmCreatedAt dtmCreatedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE ublActivityType CHANGE dtmCreatedAt dtmCreatedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE ublBusinessBranch CHANGE dtmCreatedAt dtmCreatedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE ublCountry CHANGE dtmCreatedAt dtmCreatedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE ublCurrency CHANGE dtmCreatedAt dtmCreatedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE ublIndustry CHANGE dtmCreatedAt dtmCreatedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE ublPriority CHANGE dtmCreatedAt dtmCreatedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE ublProjectSaleType CHANGE dtmCreatedAt dtmCreatedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE ublStatus CHANGE dtmCreatedAt dtmCreatedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE ublTechnology CHANGE dtmCreatedAt dtmCreatedAt DATETIME DEFAULT NULL');
    }
}
