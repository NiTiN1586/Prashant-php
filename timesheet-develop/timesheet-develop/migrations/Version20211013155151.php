<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211013155151 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Alter Git Branch Table Columns Migration';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tblGitBranch DROP FOREIGN KEY FK_FF62290F3281966B');
        $this->addSql('DROP INDEX IDX_FF62290F3281966B ON tblGitBranch');
        $this->addSql('ALTER TABLE tblGitBranch ADD intTaskId INT DEFAULT NULL, CHANGE intwitcherprojectid intGitLabProjectId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tblGitBranch ADD CONSTRAINT FK_FF62290F3133CCD4 FOREIGN KEY (intGitLabProjectId) REFERENCES tblGitLabProject (intId)');
        $this->addSql('ALTER TABLE tblGitBranch ADD CONSTRAINT FK_FF62290FAEAE0B42 FOREIGN KEY (intTaskId) REFERENCES tblTask (intId)');
        $this->addSql('CREATE INDEX IDX_FF62290F3133CCD4 ON tblGitBranch (intGitLabProjectId)');
        $this->addSql('CREATE INDEX IDX_FF62290FAEAE0B42 ON tblGitBranch (intTaskId)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE tblGitBranch DROP FOREIGN KEY FK_FF62290F3133CCD4');
        $this->addSql('ALTER TABLE tblGitBranch DROP FOREIGN KEY FK_FF62290FAEAE0B42');
        $this->addSql('DROP INDEX IDX_FF62290F3133CCD4 ON tblGitBranch');
        $this->addSql('DROP INDEX IDX_FF62290FAEAE0B42 ON tblGitBranch');
        $this->addSql('ALTER TABLE tblGitBranch ADD intWitcherProjectId INT DEFAULT NULL, DROP intGitLabProjectId, DROP intTaskId');
        $this->addSql('ALTER TABLE tblGitBranch ADD CONSTRAINT FK_FF62290F3281966B FOREIGN KEY (intWitcherProjectId) REFERENCES tblWitcherProject (intId)');
        $this->addSql('CREATE INDEX IDX_FF62290F3281966B ON tblGitBranch (intWitcherProjectId)');
    }
}
