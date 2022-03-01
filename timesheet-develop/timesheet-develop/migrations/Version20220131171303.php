<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220131171303 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Implement sprint structure WITCHER-295';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sprint (id INT AUTO_INCREMENT NOT NULL, witcher_project INT NOT NULL, name VARCHAR(255) NOT NULL, started_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ended_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', completed_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', description TEXT DEFAULT NULL, closed TINYINT(1) DEFAULT \'0\' NOT NULL, external_id INT DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_sprint_witcherProject (witcher_project), INDEX IDX_sprint_createdAt (created_at), INDEX IDX_sprint_deletedAt (deleted_at), INDEX IDX_sprint_closed (closed), INDEX IDX_sprint_startedAt_endedAt (started_at, ended_at), INDEX IDX_sprint_endedAt (ended_at), INDEX IDX_sprint_completedAt (completed_at), UNIQUE INDEX UK_sprint_externalId (external_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sprint_task (sprint INT NOT NULL, task INT NOT NULL, INDEX IDX_69BC7409EF8055B7 (sprint), INDEX IDX_69BC7409527EDB25 (task), PRIMARY KEY(sprint, task)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sprint ADD CONSTRAINT FK_EF8055B770D1B332 FOREIGN KEY (witcher_project) REFERENCES witcher_project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sprint_task ADD CONSTRAINT FK_69BC7409EF8055B7 FOREIGN KEY (sprint) REFERENCES sprint (id)');
        $this->addSql('ALTER TABLE sprint_task ADD CONSTRAINT FK_69BC7409527EDB25 FOREIGN KEY (task) REFERENCES task (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sprint_task DROP FOREIGN KEY FK_69BC7409EF8055B7');
        $this->addSql('DROP TABLE sprint');
        $this->addSql('DROP TABLE sprint_task');
    }
}
