<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211008142506 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Amend Issue Change Log Tables';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblChangeLogItem DROP FOREIGN KEY FK_B46EDA446B5801FC');
        $this->addSql('DROP INDEX idx_b46eda446b5801fc ON tblChangeLogItem');
        $this->addSql('CREATE INDEX IDX_B46EDA448E2DE239 ON tblChangeLogItem (intHistoryChangeLogId)');
        $this->addSql('ALTER TABLE tblChangeLogItem ADD CONSTRAINT FK_B46EDA446B5801FC FOREIGN KEY (intHistoryChangeLogId) REFERENCES tblHistoryChangeLog (intId)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblChangeLogItem DROP FOREIGN KEY FK_B46EDA448E2DE239');
        $this->addSql('DROP INDEX idx_b46eda448e2de239 ON tblChangeLogItem');
        $this->addSql('CREATE INDEX IDX_B46EDA446B5801FC ON tblChangeLogItem (intHistoryChangeLogId)');
        $this->addSql('ALTER TABLE tblChangeLogItem ADD CONSTRAINT FK_B46EDA448E2DE239 FOREIGN KEY (intHistoryChangeLogId) REFERENCES tblHistoryChangeLog (intId)');
    }
}
