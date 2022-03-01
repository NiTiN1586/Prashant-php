<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211126083901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Modified tablename, fieldname, indexname as per new code style guideline (as per ticket WITCHER-208)';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblAddress DROP FOREIGN KEY FK_B666D82182FE0558');

        $this->addSql('ALTER TABLE tblGoogleAccount DROP FOREIGN KEY FK_CCE82BE919578CB9');

        $this->addSql('ALTER TABLE tblUserProfile DROP FOREIGN KEY FK_5E1F5A133F9F13EC');

        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, user INT NOT NULL, street VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, state VARCHAR(255) NOT NULL, country VARCHAR(50) NOT NULL, postal_code VARCHAR(20) NOT NULL, UNIQUE INDEX UK_address_user (user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE google_account (id INT AUTO_INCREMENT NOT NULL, user INT DEFAULT NULL, google_account_id VARCHAR(30) NOT NULL, email VARCHAR(40) NOT NULL, first_name VARCHAR(40) NOT NULL, last_name VARCHAR(40) NOT NULL, avatar_url VARCHAR(200) DEFAULT NULL, INDEX IDX_google_account_user (user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, invitation_email VARCHAR(40) NOT NULL, created_at DATETIME DEFAULT NULL, last_login DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, UNIQUE INDEX UK_user_invitationEmail (invitation_email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE user_profile (id INT AUTO_INCREMENT NOT NULL, user INT NOT NULL, first_name VARCHAR(20) DEFAULT NULL, last_name VARCHAR(20) DEFAULT NULL, company VARCHAR(20) NOT NULL, iban VARCHAR(34) NOT NULL, vat VARCHAR(20) NOT NULL, bank_name VARCHAR(50) NOT NULL, swift_code VARCHAR(11) NOT NULL, INDEX IDX_user_profile_user (user), UNIQUE INDEX IDX_user__user (user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F818D93D649 FOREIGN KEY (user) REFERENCES user (id) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE google_account ADD CONSTRAINT FK_83726B228D93D649 FOREIGN KEY (user) REFERENCES user (id) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE user_profile ADD CONSTRAINT FK_D95AB4058D93D649 FOREIGN KEY (user) REFERENCES user (id) ON DELETE CASCADE');

        $this->addSql('DROP TABLE tblAddress');

        $this->addSql('DROP TABLE tblGoogleAccount');

        $this->addSql('DROP TABLE tblUser');

        $this->addSql('DROP TABLE tblUserProfile');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F818D93D649');

        $this->addSql('ALTER TABLE google_account DROP FOREIGN KEY FK_83726B228D93D649');

        $this->addSql('ALTER TABLE user_profile DROP FOREIGN KEY FK_D95AB4058D93D649');

        $this->addSql('CREATE TABLE tblAddress (intAddressId INT AUTO_INCREMENT NOT NULL, strStreet VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, strCity VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, strState VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, strCountry VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, strPostalCode VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(intAddressId)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');

        $this->addSql('CREATE TABLE tblGoogleAccount (intGoogleAccountId INT AUTO_INCREMENT NOT NULL, strGoogleAccountId VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, strFirstName VARCHAR(40) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, strLastName VARCHAR(40) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, strAvatarUrl VARCHAR(200) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, intUserId INT DEFAULT NULL, strEmail VARCHAR(40) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_CCE82BE919578CB9 (intUserId), PRIMARY KEY(intGoogleAccountId)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');

        $this->addSql('CREATE TABLE tblUser (intUserId INT AUTO_INCREMENT NOT NULL, dtmCreatedAt DATETIME DEFAULT NULL, dtmLastLogin DATETIME DEFAULT NULL, bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, strInvitationEmail VARCHAR(40) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_9050B930E2EE2BE7 (strInvitationEmail), PRIMARY KEY(intUserId)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');

        $this->addSql('CREATE TABLE tblUserProfile (intUserProfileId INT AUTO_INCREMENT NOT NULL, strFirstName VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, strLastName VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, strCompany VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, strIban VARCHAR(34) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, strVat VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, strBankName VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, strSwiftCode VARCHAR(11) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(intUserProfileId)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');

        $this->addSql('ALTER TABLE tblAddress ADD CONSTRAINT FK_B666D82182FE0558 FOREIGN KEY (intAddressId) REFERENCES tblUser (intUserId) ON UPDATE NO ACTION ON DELETE CASCADE');

        $this->addSql('ALTER TABLE tblGoogleAccount ADD CONSTRAINT FK_CCE82BE919578CB9 FOREIGN KEY (intUserId) REFERENCES tblUser (intUserId) ON UPDATE NO ACTION ON DELETE CASCADE');

        $this->addSql('ALTER TABLE tblUserProfile ADD CONSTRAINT FK_5E1F5A133F9F13EC FOREIGN KEY (intUserProfileId) REFERENCES tblUser (intUserId) ON UPDATE NO ACTION ON DELETE CASCADE');

        $this->addSql('DROP TABLE address');

        $this->addSql('DROP TABLE google_account');

        $this->addSql('DROP TABLE user');

        $this->addSql('DROP TABLE user_profile');
    }
}
