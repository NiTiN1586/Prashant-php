<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210922131203 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Project and User tables modification';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblGitLabProject CHANGE straccesstoken strGitLabProjectId VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE tblProject CHANGE intClientId intClientId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tblWitcherUser DROP strJiraAccessToken');
        $this->addSql('ALTER TABLE tblProject CHANGE strRepositoryId strRepositoryId VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE tblProject CHANGE strConfluenceLink strConfluenceLink VARCHAR(150) DEFAULT NULL');
        $this->addSql('ALTER TABLE tblProject ADD strDescription VARCHAR(400) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblGitLabProject CHANGE strgitlabprojectid strAccessToken VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE tblProject CHANGE intClientId intClientId INT NOT NULL');
        $this->addSql('ALTER TABLE tblWitcherUser ADD strJiraAccessToken VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE tblProject CHANGE strRepositoryId strRepositoryId VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE tblProject CHANGE strConfluenceLink strConfluenceLink VARCHAR(150) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE tblProject DROP strDescription');
    }
}
