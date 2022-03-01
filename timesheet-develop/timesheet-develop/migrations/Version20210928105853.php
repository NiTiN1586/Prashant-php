<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210928105853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Alter Jira Project Table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblJiraProject DROP FOREIGN KEY FK_C7EC18AE63E1464C');
        $this->addSql('ALTER TABLE tblJiraProject DROP FOREIGN KEY FK_C7EC18AE7506BE97');
        $this->addSql('DROP INDEX IDX_C7EC18AE7506BE97 ON tblJiraProject');
        $this->addSql('DROP INDEX IDX_C7EC18AE63E1464C ON tblJiraProject');
        $this->addSql('ALTER TABLE tblJiraProject DROP strName, DROP strDescription, DROP intWictherUserId, DROP intSaleTypeId');
        $this->addSql('ALTER TABLE tblWitcherProject CHANGE intClientId intClientId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tblJiraProject ADD strConfluenceLink VARCHAR(150) DEFAULT NULL');
        $this->addSql('ALTER TABLE tblWitcherProject DROP strConfluenceLink');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblJiraProject ADD strName VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD strDescription VARCHAR(400) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD intWictherUserId INT DEFAULT NULL, ADD intSaleTypeId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tblJiraProject ADD CONSTRAINT FK_C7EC18AE63E1464C FOREIGN KEY (intSaleTypeId) REFERENCES ublProjectSaleType (intId) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE tblJiraProject ADD CONSTRAINT FK_C7EC18AE7506BE97 FOREIGN KEY (intWictherUserId) REFERENCES tblWitcherUser (intId) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_C7EC18AE7506BE97 ON tblJiraProject (intWictherUserId)');
        $this->addSql('CREATE INDEX IDX_C7EC18AE63E1464C ON tblJiraProject (intSaleTypeId)');
        $this->addSql('ALTER TABLE tblWitcherProject CHANGE intClientId intClientId INT NOT NULL');
        $this->addSql('ALTER TABLE tblJiraProject DROP strConfluenceLink');
        $this->addSql('ALTER TABLE tblWitcherProject ADD strConfluenceLink VARCHAR(150) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
