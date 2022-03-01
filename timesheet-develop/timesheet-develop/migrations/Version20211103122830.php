<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211103122830 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create new ActivityType and define relation into Activity entity and values added into ActivityType WITCHER-55 and WITCHER-55';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity_type (intId INT AUTO_INCREMENT NOT NULL, strFriendlyName VARCHAR(30) NOT NULL, strHandle VARCHAR(30) NOT NULL, intDisplayOrder INT DEFAULT NULL, dtmCreatedAt DATETIME DEFAULT NULL, bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_activity_type_intDisplayOrder (intDisplayOrder), INDEX IDX_activity_type_dtmCreatedAt (dtmCreatedAt), INDEX IDX_activity_type_bolActive (bolActive), INDEX IDX_activity_type_deletedAt (deleted_at), UNIQUE INDEX UK_activity_type_strHandle (strHandle), PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tblActivity ADD activity_type INT NOT NULL');
        $this->addSql('ALTER TABLE tblActivity ADD CONSTRAINT FK_7A1982058F1A8CBB FOREIGN KEY (activity_type) REFERENCES activity_type (intId) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_tblActivity_activity_type ON tblActivity (activity_type)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblActivity DROP FOREIGN KEY FK_7A1982058F1A8CBB');
        $this->addSql('DROP TABLE activity_type');
        $this->addSql('DROP INDEX IDX_tblActivity_activity_type ON tblActivity');
        $this->addSql('ALTER TABLE tblActivity DROP activity_type');
    }

    public function postUp(Schema $schema): void
    {
        $this->connection->executeQuery("INSERT IGNORE INTO activity_type (strFriendlyName, strHandle, intDisplayOrder, bolActive) VALUES ('Development','DEVELOPMENT', 1, true)");
        $this->connection->executeQuery("INSERT IGNORE INTO activity_type (strFriendlyName, strHandle, intDisplayOrder, bolActive) VALUES ('Debug','DEBUG', 2, true)");
        $this->connection->executeQuery("INSERT IGNORE INTO activity_type (strFriendlyName, strHandle, intDisplayOrder, bolActive) VALUES ('Code Review','CODE_REVIEW', 3, true)");
        $this->connection->executeQuery("INSERT IGNORE INTO activity_type (strFriendlyName, strHandle, intDisplayOrder, bolActive) VALUES ('Pair Programming','PAIR_PROGRAMMING', 4, true)");
        $this->connection->executeQuery("INSERT IGNORE INTO activity_type (strFriendlyName, strHandle, intDisplayOrder, bolActive) VALUES ('Meeting','MEETING', 5, true)");
    }
}
