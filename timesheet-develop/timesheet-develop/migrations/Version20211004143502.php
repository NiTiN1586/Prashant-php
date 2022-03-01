<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211004143502 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Modify entities.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblTask ADD intParentTaskId INT DEFAULT NULL, CHANGE strJiraLink strJiraLink VARCHAR(200) DEFAULT NULL');
        $this->addSql('ALTER TABLE tblTask ADD CONSTRAINT FK_4FBDB45C2F9A3779 FOREIGN KEY (intParentTaskId) REFERENCES tblTask (intId)');
        $this->addSql('CREATE INDEX IDX_4FBDB45C2F9A3779 ON tblTask (intParentTaskId)');
        $this->addSql('ALTER TABLE tblTask ADD bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, ADD intCreatorId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tblTask ADD CONSTRAINT FK_4FBDB45C736452D1 FOREIGN KEY (intCreatorId) REFERENCES tblWitcherUser (intId) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_4FBDB45C736452D1 ON tblTask (intCreatorId)');
        $this->addSql('ALTER TABLE tblTask ADD dtmUpdatedAt DATETIME DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UK_tblTask_handle ON tblTask (strHandle)');
        $this->addSql('DROP INDEX UNIQ_650105485AE2AC1B ON tblWitcherProjectActivityType');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblTask DROP FOREIGN KEY FK_4FBDB45C2F9A3779');
        $this->addSql('DROP INDEX IDX_4FBDB45C2F9A3779 ON tblTask');
        $this->addSql('ALTER TABLE tblTask DROP intParentTaskId, CHANGE strJiraLink strJiraLink VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE tblTask DROP FOREIGN KEY FK_4FBDB45C736452D1');
        $this->addSql('DROP INDEX IDX_4FBDB45C736452D1 ON tblTask');
        $this->addSql('ALTER TABLE tblTask DROP bolActive, DROP intCreatorId');
        $this->addSql('ALTER TABLE tblTask DROP dtmUpdatedAt');
        $this->addSql('DROP INDEX UK_tblTask_handle ON tblTask');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_650105485AE2AC1B ON tblWitcherProjectActivityType (intActivityTypeId)');
    }
}
