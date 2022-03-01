<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210519213246 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creating User and GoogleAccount tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE tblGoogleAccount (
                intGoogleAccountId INT AUTO_INCREMENT NOT NULL,
                strGoogleAccountId VARCHAR(30) NOT NULL, 
                strFirstName VARCHAR(40) NOT NULL, 
                strLastName VARCHAR(40) NOT NULL, 
                strAvatarUrl VARCHAR(200) DEFAULT NULL, 
                intUserId INT DEFAULT NULL, 
                INDEX IDX_CCE82BE919578CB9 (intUserId), 
                PRIMARY KEY(intGoogleAccountId)
            ) 
            DEFAULT CHARACTER SET utf8mb4 
            COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
        $this->addSql('
            CREATE TABLE tblUser (
                intUserId INT AUTO_INCREMENT NOT NULL, 
                dtmCreatedAt DATETIME DEFAULT NULL, 
                dtmLastLogin DATETIME DEFAULT NULL, 
                bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, 
                PRIMARY KEY(intUserId)
            ) 
            DEFAULT CHARACTER SET utf8mb4 
            COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
        $this->addSql('
                ALTER TABLE tblGoogleAccount 
                    ADD CONSTRAINT FK_CCE82BE919578CB9 
                    FOREIGN KEY (intUserId) 
                    REFERENCES tblUser (intUserId) 
                    ON DELETE SET NULL
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tblGoogleAccount DROP FOREIGN KEY FK_CCE82BE919578CB9');
        $this->addSql('DROP TABLE tblGoogleAccount');
        $this->addSql('DROP TABLE tblUser');
    }
}
