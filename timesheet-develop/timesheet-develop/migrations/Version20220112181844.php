<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220112181844 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove unused tracker_account functionality';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tracker_task_assignment DROP FOREIGN KEY FK_2FD272976D6E0F2');
        $this->addSql('ALTER TABLE tracker_task_assignment DROP FOREIGN KEY FK_2FD27297C9DFC0C');
        $this->addSql('ALTER TABLE tracker_task_assignment DROP FOREIGN KEY FK_2FD2729BC06EA63');
        $this->addSql('DROP TABLE tracker_account');
        $this->addSql('DROP TABLE tracker_task_assignment');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tracker_account (id INT AUTO_INCREMENT NOT NULL, account_id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UK_tracker_account_accountId (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tracker_task_assignment (id INT AUTO_INCREMENT NOT NULL, assignee INT DEFAULT NULL, reporter INT DEFAULT NULL, creator INT DEFAULT NULL, task INT NOT NULL, INDEX IDX_tracker_task_assignment_assignee (assignee), INDEX IDX_tracker_task_assignment_creator (creator), INDEX IDX_tracker_task_assignment_reporter (reporter), INDEX IDX_tracker_task_assignment_task (task), UNIQUE INDEX UK_tracker_task_assignment_task (task), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE tracker_task_assignment ADD CONSTRAINT FK_2FD2729527EDB25 FOREIGN KEY (task) REFERENCES task (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE tracker_task_assignment ADD CONSTRAINT FK_2FD272976D6E0F2 FOREIGN KEY (reporter) REFERENCES tracker_account (id) ON UPDATE NO ACTION ON DELETE SET NULL');
        $this->addSql('ALTER TABLE tracker_task_assignment ADD CONSTRAINT FK_2FD27297C9DFC0C FOREIGN KEY (assignee) REFERENCES tracker_account (id) ON UPDATE NO ACTION ON DELETE SET NULL');
        $this->addSql('ALTER TABLE tracker_task_assignment ADD CONSTRAINT FK_2FD2729BC06EA63 FOREIGN KEY (creator) REFERENCES tracker_account (id) ON UPDATE NO ACTION ON DELETE SET NULL');
    }
}
