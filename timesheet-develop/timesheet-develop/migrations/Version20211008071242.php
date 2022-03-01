<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211008071242 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Issue Change Log Tables';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tblChangeLogItem (intId INT AUTO_INCREMENT NOT NULL, strField VARCHAR(50) NOT NULL, strFieldType VARCHAR(20) NOT NULL, strChangeFrom VARCHAR(2500) DEFAULT NULL, strChangeTo VARCHAR(2500) DEFAULT NULL, intHistoryChangeLogId INT NOT NULL, INDEX IDX_B46EDA446B5801FC (intHistoryChangeLogId), PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tblHistoryChangeLog (intId INT AUTO_INCREMENT NOT NULL, dtmCreatedAt DATETIME DEFAULT NULL, intCreatorId INT DEFAULT NULL, intTaskId INT NOT NULL, INDEX IDX_A80D0277736452D1 (intCreatorId), INDEX IDX_A80D0277AEAE0B42 (intTaskId), PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tblChangeLogItem ADD CONSTRAINT FK_B46EDA446B5801FC FOREIGN KEY (intHistoryChangeLogId) REFERENCES tblHistoryChangeLog (intId)');
        $this->addSql('ALTER TABLE tblHistoryChangeLog ADD CONSTRAINT FK_A80D0277736452D1 FOREIGN KEY (intCreatorId) REFERENCES tblWitcherUser (intId)');
        $this->addSql('ALTER TABLE tblHistoryChangeLog ADD CONSTRAINT FK_A80D0277AEAE0B42 FOREIGN KEY (intTaskId) REFERENCES tblTask (intId)');
        $this->addSql('ALTER TABLE tblHistoryChangeLog ADD intExternalId INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblChangeLogItem DROP FOREIGN KEY FK_B46EDA446B5801FC');
        $this->addSql('DROP TABLE tblChangeLogItem');
        $this->addSql('DROP TABLE tblHistoryChangeLog');
        $this->addSql('ALTER TABLE tblHistoryChangeLog DROP intExternalId');
    }
}
