<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210928144318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Project Activity Type, Task changes';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tblWitcherProjectActivityType (intId INT AUTO_INCREMENT NOT NULL, bolSubTaskLevel TINYINT(1) DEFAULT \'0\' NOT NULL, intDisplayOrder INT DEFAULT NULL, bolActive TINYINT(1) DEFAULT \'1\' NOT NULL, dtmCreatedAt DATETIME DEFAULT NULL, intWitcherProjectId INT NOT NULL, intActivityTypeId INT NOT NULL, INDEX IDX_650105483281966B (intWitcherProjectId), UNIQUE INDEX UNIQ_650105485AE2AC1B (intActivityTypeId), PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tblWitcherProjectActivityType ADD CONSTRAINT FK_650105483281966B FOREIGN KEY (intWitcherProjectId) REFERENCES tblWitcherProject (intId) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tblWitcherProjectActivityType ADD CONSTRAINT FK_650105485AE2AC1B FOREIGN KEY (intActivityTypeId) REFERENCES ublActivityType (intId) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tblTask DROP FOREIGN KEY FK_4FBDB45C5AE2AC1B');
        $this->addSql('ALTER TABLE tblTask ADD CONSTRAINT FK_4FBDB45C5AE2AC1B FOREIGN KEY (intActivityTypeId) REFERENCES tblWitcherProjectActivityType (intId)');
        $this->addSql('ALTER TABLE tblJiraProject ADD intJiraProjectId INT NOT NULL, DROP strConfluenceLink');
        $this->addSql('ALTER TABLE tblWitcherProject ADD strConfluenceLink VARCHAR(150) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblTask DROP FOREIGN KEY FK_4FBDB45C5AE2AC1B');
        $this->addSql('DROP TABLE tblWitcherProjectActivityType');
        $this->addSql('ALTER TABLE tblTask DROP FOREIGN KEY FK_4FBDB45C5AE2AC1B');
        $this->addSql('ALTER TABLE tblTask ADD CONSTRAINT FK_4FBDB45C5AE2AC1B FOREIGN KEY (intActivityTypeId) REFERENCES ublActivityType (intId)');
        $this->addSql('ALTER TABLE tblJiraProject ADD strConfluenceLink VARCHAR(150) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP intJiraProjectId');
        $this->addSql('ALTER TABLE tblWitcherProject DROP strConfluenceLink');
    }
}
