<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220218153429 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update sprint task relations WITCHER-392';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE task_sprint (task INT NOT NULL, sprint INT NOT NULL, INDEX IDX_D429D164527EDB25 (task), INDEX IDX_D429D164EF8055B7 (sprint), PRIMARY KEY(task, sprint)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE task_sprint ADD CONSTRAINT FK_D429D164527EDB25 FOREIGN KEY (task) REFERENCES task (id)');
        $this->addSql('ALTER TABLE task_sprint ADD CONSTRAINT FK_D429D164EF8055B7 FOREIGN KEY (sprint) REFERENCES sprint (id)');
        $this->addSql('DROP TABLE sprint_task');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sprint_task (sprint INT NOT NULL, task INT NOT NULL, INDEX IDX_69BC7409527EDB25 (task), INDEX IDX_69BC7409EF8055B7 (sprint), PRIMARY KEY(sprint, task)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE sprint_task ADD CONSTRAINT FK_69BC7409527EDB25 FOREIGN KEY (task) REFERENCES task (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE sprint_task ADD CONSTRAINT FK_69BC7409EF8055B7 FOREIGN KEY (sprint) REFERENCES sprint (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE task_sprint');
    }
}
