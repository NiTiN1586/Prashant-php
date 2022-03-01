<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211108175200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'rename tblHistoryChangeLog table to tracker_history_change_log as per ticket WITCHER-154';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblChangeLogItem DROP FOREIGN KEY FK_B46EDA446B5801FC');
        $this->addSql('CREATE TABLE tracker_history_change_log (intId INT AUTO_INCREMENT NOT NULL, intExternalId INT DEFAULT NULL, dtmCreatedAt DATETIME DEFAULT NULL, intCreatorId INT DEFAULT NULL, intTaskId INT NOT NULL, INDEX IDX_tracker_history_change_log_intCreatorId (intCreatorId), INDEX IDX_tracker_history_change_log_intTaskId (intTaskId), PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tracker_history_change_log ADD CONSTRAINT FK_CC188BEF736452D1 FOREIGN KEY (intCreatorId) REFERENCES tblWitcherUser (intId)');
        $this->addSql('ALTER TABLE tracker_history_change_log ADD CONSTRAINT FK_CC188BEFAEAE0B42 FOREIGN KEY (intTaskId) REFERENCES tblTask (intId)');
        $this->addSql('DROP TABLE tblHistoryChangeLog');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tracker_history_change_log_item DROP FOREIGN KEY FK_A7985EBBCC188BEF');
        $this->addSql('CREATE TABLE tblHistoryChangeLog (intId INT AUTO_INCREMENT NOT NULL, dtmCreatedAt DATETIME DEFAULT NULL, intCreatorId INT DEFAULT NULL, intTaskId INT NOT NULL, intExternalId INT DEFAULT NULL, INDEX IDX_A80D0277736452D1 (intCreatorId), INDEX IDX_A80D0277AEAE0B42 (intTaskId), PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE tblHistoryChangeLog ADD CONSTRAINT FK_A80D0277736452D1 FOREIGN KEY (intCreatorId) REFERENCES tblWitcherUser (intId) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE tblHistoryChangeLog ADD CONSTRAINT FK_A80D0277AEAE0B42 FOREIGN KEY (intTaskId) REFERENCES tblTask (intId) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE tracker_history_change_log');
    }
}
