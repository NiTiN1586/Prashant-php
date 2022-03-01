<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211012140402 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Git Branch Table Migration';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tblGitBranch (intId INT AUTO_INCREMENT NOT NULL, strBranchName VARCHAR(100) NOT NULL, strWebUrl VARCHAR(200) NOT NULL, bolMerged TINYINT(1) DEFAULT \'0\' NOT NULL, dtmCreatedAt DATETIME DEFAULT NULL, intWitcherProjectId INT DEFAULT NULL, INDEX IDX_FF62290F3281966B (intWitcherProjectId), UNIQUE INDEX UK_tblGitBranch_strBranchName (strBranchName), PRIMARY KEY(intId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tblGitBranch ADD CONSTRAINT FK_FF62290F3281966B FOREIGN KEY (intWitcherProjectId) REFERENCES tblWitcherProject (intId)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE tblGitBranch');
    }
}
