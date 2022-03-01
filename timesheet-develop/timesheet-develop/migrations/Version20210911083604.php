<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210911083604 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migrate Witcher Database Structure';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tblClient (intId INT AUTO_INCREMENT NOT NULL, strName VARCHAR(50) NOT NULL, dtmCreatedAt DATETIME DEFAULT NULL, bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, intCountryId INT DEFAULT NULL, intIndustryId INT DEFAULT NULL, intCurrencyId INT DEFAULT NULL, INDEX IDX_E4B4A7E0AC743893 (intCountryId), INDEX IDX_E4B4A7E065E37C67 (intIndustryId), INDEX IDX_E4B4A7E0345D687F (intCurrencyId), PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tblProject (intId INT AUTO_INCREMENT NOT NULL, strName VARCHAR(50) NOT NULL, strRepositoryId VARCHAR(50) NOT NULL, strConfluenceLink VARCHAR(150) NOT NULL, bolIsBillable TINYINT(1) DEFAULT \'1\' NOT NULL, dtmCreatedAt DATETIME DEFAULT NULL, bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, intBusinessBranchId INT DEFAULT NULL, intWictherUserId INT DEFAULT NULL, intClientId INT NOT NULL, intSaleTypeId INT DEFAULT NULL, INDEX IDX_949B674EC096CAA3 (intBusinessBranchId), INDEX IDX_949B674E7506BE97 (intWictherUserId), INDEX IDX_949B674E5437EBCA (intClientId), INDEX IDX_949B674E63E1464C (intSaleTypeId), PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tblWitcherUser (intId INT AUTO_INCREMENT NOT NULL, intJagaadUserId INT NOT NULL, jsonWitcherRoles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', dtmCreatedAt DATETIME DEFAULT NULL, bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ublBusinessBranch (intId INT AUTO_INCREMENT NOT NULL, strFriendlyName VARCHAR(30) NOT NULL, strHandle VARCHAR(30) NOT NULL, intDisplayOrder INT DEFAULT NULL, dtmCreatedAt DATETIME DEFAULT NULL, bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ublCountry (intId INT AUTO_INCREMENT NOT NULL, strFriendlyName VARCHAR(30) NOT NULL, strHandle VARCHAR(30) NOT NULL, intDisplayOrder INT DEFAULT NULL, dtmCreatedAt DATETIME DEFAULT NULL, bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ublCurrency (intId INT AUTO_INCREMENT NOT NULL, strFriendlyName VARCHAR(30) NOT NULL, strHandle VARCHAR(30) NOT NULL, intDisplayOrder INT DEFAULT NULL, dtmCreatedAt DATETIME DEFAULT NULL, bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ublIndustry (intId INT AUTO_INCREMENT NOT NULL, strFriendlyName VARCHAR(30) NOT NULL, strHandle VARCHAR(30) NOT NULL, intDisplayOrder INT DEFAULT NULL, dtmCreatedAt DATETIME DEFAULT NULL, bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ublProjectSaleType (intId INT AUTO_INCREMENT NOT NULL, strFriendlyName VARCHAR(30) NOT NULL, strHandle VARCHAR(30) NOT NULL, intDisplayOrder INT DEFAULT NULL, dtmCreatedAt DATETIME DEFAULT NULL, bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, intBusinessBranchId INT NOT NULL, INDEX IDX_7E750F0FC096CAA3 (intBusinessBranchId), PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ublTask (intId INT AUTO_INCREMENT NOT NULL, strFriendlyName VARCHAR(30) NOT NULL, strHandle VARCHAR(30) NOT NULL, intDisplayOrder INT DEFAULT NULL, dtmCreatedAt DATETIME DEFAULT NULL, bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ublTechnology (intId INT AUTO_INCREMENT NOT NULL, strFriendlyName VARCHAR(30) NOT NULL, strHandle VARCHAR(30) NOT NULL, intDisplayOrder INT DEFAULT NULL, dtmCreatedAt DATETIME DEFAULT NULL, bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tblClient ADD CONSTRAINT FK_E4B4A7E0AC743893 FOREIGN KEY (intCountryId) REFERENCES ublCountry (intId) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE tblClient ADD CONSTRAINT FK_E4B4A7E065E37C67 FOREIGN KEY (intIndustryId) REFERENCES ublIndustry (intId) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE tblClient ADD CONSTRAINT FK_E4B4A7E0345D687F FOREIGN KEY (intCurrencyId) REFERENCES ublCurrency (intId) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE tblProject ADD CONSTRAINT FK_949B674EC096CAA3 FOREIGN KEY (intBusinessBranchId) REFERENCES ublBusinessBranch (intId) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE tblProject ADD CONSTRAINT FK_949B674E7506BE97 FOREIGN KEY (intWictherUserId) REFERENCES tblWitcherUser (intId) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE tblProject ADD CONSTRAINT FK_949B674E5437EBCA FOREIGN KEY (intClientId) REFERENCES tblClient (intId) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tblProject ADD CONSTRAINT FK_949B674E63E1464C FOREIGN KEY (intSaleTypeId) REFERENCES ublProjectSaleType (intId) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE ublProjectSaleType ADD CONSTRAINT FK_7E750F0FC096CAA3 FOREIGN KEY (intBusinessBranchId) REFERENCES ublBusinessBranch (intId) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblProject DROP FOREIGN KEY FK_949B674E5437EBCA');
        $this->addSql('ALTER TABLE tblProject DROP FOREIGN KEY FK_949B674E7506BE97');
        $this->addSql('ALTER TABLE tblProject DROP FOREIGN KEY FK_949B674EC096CAA3');
        $this->addSql('ALTER TABLE ublProjectSaleType DROP FOREIGN KEY FK_7E750F0FC096CAA3');
        $this->addSql('ALTER TABLE tblClient DROP FOREIGN KEY FK_E4B4A7E0AC743893');
        $this->addSql('ALTER TABLE tblClient DROP FOREIGN KEY FK_E4B4A7E0345D687F');
        $this->addSql('ALTER TABLE tblClient DROP FOREIGN KEY FK_E4B4A7E065E37C67');
        $this->addSql('ALTER TABLE tblProject DROP FOREIGN KEY FK_949B674E63E1464C');
        $this->addSql('DROP TABLE tblClient');
        $this->addSql('DROP TABLE tblProject');
        $this->addSql('DROP TABLE tblWitcherUser');
        $this->addSql('DROP TABLE ublBusinessBranch');
        $this->addSql('DROP TABLE ublCountry');
        $this->addSql('DROP TABLE ublCurrency');
        $this->addSql('DROP TABLE ublIndustry');
        $this->addSql('DROP TABLE ublProjectSaleType');
        $this->addSql('DROP TABLE ublTask');
        $this->addSql('DROP TABLE ublTechnology');
    }
}
