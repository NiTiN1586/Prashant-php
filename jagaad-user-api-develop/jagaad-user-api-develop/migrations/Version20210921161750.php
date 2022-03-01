<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210921161750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'User Profile Migration';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tblAddress (intAddressId INT AUTO_INCREMENT NOT NULL, strStreet VARCHAR(255) NOT NULL, strCity VARCHAR(255) NOT NULL, strState VARCHAR(255) NOT NULL, strCountry VARCHAR(50) NOT NULL, strPostalCode VARCHAR(20) NOT NULL, PRIMARY KEY(intAddressId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tblUserProfile (intUserProfileId INT AUTO_INCREMENT NOT NULL, strFirstName VARCHAR(20) DEFAULT NULL, strLastName VARCHAR(20) DEFAULT NULL, strCompany VARCHAR(20) NOT NULL, strIban VARCHAR(34) NOT NULL, strVat VARCHAR(20) NOT NULL, strBankName VARCHAR(50) NOT NULL, strSwiftCode VARCHAR(11) NOT NULL, PRIMARY KEY(intUserProfileId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tblAddress ADD CONSTRAINT FK_B666D82182FE0558 FOREIGN KEY (intAddressId) REFERENCES tblUser (intUserId) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tblUserProfile ADD CONSTRAINT FK_5E1F5A133F9F13EC FOREIGN KEY (intUserProfileId) REFERENCES tblUser (intUserId) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tblGoogleAccount DROP FOREIGN KEY FK_CCE82BE919578CB9');
        $this->addSql('ALTER TABLE tblGoogleAccount ADD CONSTRAINT FK_CCE82BE919578CB9 FOREIGN KEY (intUserId) REFERENCES tblUser (intUserId) ON DELETE CASCADE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9050B930E2EE2BE7 ON tblUser (strInvitationEmail)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE tblAddress');
        $this->addSql('DROP TABLE tblUserProfile');
        $this->addSql('ALTER TABLE tblGoogleAccount DROP FOREIGN KEY FK_CCE82BE919578CB9');
        $this->addSql('ALTER TABLE tblGoogleAccount ADD CONSTRAINT FK_CCE82BE919578CB9 FOREIGN KEY (intUserId) REFERENCES tblUser (intUserId) ON DELETE SET NULL');
        $this->addSql('DROP INDEX UNIQ_9050B930E2EE2BE7 ON tblUser');
    }
}
