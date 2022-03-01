<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211108180341 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'rename tblChangeLogItem table to tracker_history_change_log_item as per ticket WITCHER-154';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs\
        $this->addSql('CREATE TABLE tracker_history_change_log_item (tracker_history_change_log INT NOT NULL, intId INT AUTO_INCREMENT NOT NULL, strField VARCHAR(50) NOT NULL, strFieldType VARCHAR(20) NOT NULL, strChangeFrom VARCHAR(2500) DEFAULT NULL, strChangeTo VARCHAR(2500) DEFAULT NULL, INDEX IDX_tracker_history_change_log_item_tracker_history_change_log (tracker_history_change_log), PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tracker_history_change_log_item ADD CONSTRAINT FK_A7985EBBCC188BEF FOREIGN KEY (tracker_history_change_log) REFERENCES tracker_history_change_log (intId)');
        $this->addSql('DROP TABLE tblChangeLogItem');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tblChangeLogItem (intId INT AUTO_INCREMENT NOT NULL, strField VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, strFieldType VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, strChangeFrom VARCHAR(2500) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, strChangeTo VARCHAR(2500) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, intHistoryChangeLogId INT NOT NULL, INDEX IDX_B46EDA448E2DE239 (intHistoryChangeLogId), PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE tblChangeLogItem ADD CONSTRAINT FK_B46EDA446B5801FC FOREIGN KEY (intHistoryChangeLogId) REFERENCES tblHistoryChangeLog (intId) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE tracker_history_change_log_item');
    }
}
