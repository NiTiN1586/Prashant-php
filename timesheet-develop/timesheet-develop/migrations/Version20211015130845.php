<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211015130845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Alter Git Branch Table Indices';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblGitBranch DROP FOREIGN KEY FK_FF62290F3133CCD4');
        $this->addSql('ALTER TABLE tblGitBranch DROP FOREIGN KEY FK_FF62290FAEAE0B42');
        $this->addSql('DROP INDEX idx_ff62290f3133ccd4 ON tblGitBranch');
        $this->addSql('CREATE INDEX IDX_tblGitBranch_intGitLabProjectId ON tblGitBranch (intGitLabProjectId)');
        $this->addSql('DROP INDEX idx_ff62290faeae0b42 ON tblGitBranch');
        $this->addSql('CREATE INDEX IDX_tblGitBranch_intTaskId ON tblGitBranch (intTaskId)');
        $this->addSql('ALTER TABLE tblGitBranch ADD CONSTRAINT FK_FF62290F3133CCD4 FOREIGN KEY (intGitLabProjectId) REFERENCES tblGitLabProject (intId)');
        $this->addSql('ALTER TABLE tblGitBranch ADD CONSTRAINT FK_FF62290FAEAE0B42 FOREIGN KEY (intTaskId) REFERENCES tblTask (intId)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblGitBranch DROP FOREIGN KEY FK_FF62290F3133CCD4');
        $this->addSql('ALTER TABLE tblGitBranch DROP FOREIGN KEY FK_FF62290FAEAE0B42');
        $this->addSql('DROP INDEX idx_tblgitbranch_intgitlabprojectid ON tblGitBranch');
        $this->addSql('CREATE INDEX IDX_FF62290F3133CCD4 ON tblGitBranch (intGitLabProjectId)');
        $this->addSql('DROP INDEX idx_tblgitbranch_inttaskid ON tblGitBranch');
        $this->addSql('CREATE INDEX IDX_FF62290FAEAE0B42 ON tblGitBranch (intTaskId)');
        $this->addSql('ALTER TABLE tblGitBranch ADD CONSTRAINT FK_FF62290F3133CCD4 FOREIGN KEY (intGitLabProjectId) REFERENCES tblGitLabProject (intId)');
        $this->addSql('ALTER TABLE tblGitBranch ADD CONSTRAINT FK_FF62290FAEAE0B42 FOREIGN KEY (intTaskId) REFERENCES tblTask (intId)');
    }
}
