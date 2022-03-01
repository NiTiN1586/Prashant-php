<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211129140722 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create label migration table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE label (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_label_createdAt (created_at), INDEX IDX_label_deletedAt (deleted_at), UNIQUE INDEX UK_label_name (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task_label (task INT NOT NULL, label INT NOT NULL, INDEX IDX_C9034BC8527EDB25 (task), INDEX IDX_C9034BC8EA750E8 (label), PRIMARY KEY(task, label)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE task_label ADD CONSTRAINT FK_C9034BC8527EDB25 FOREIGN KEY (task) REFERENCES task (id)');
        $this->addSql('ALTER TABLE task_label ADD CONSTRAINT FK_C9034BC8EA750E8 FOREIGN KEY (label) REFERENCES label (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task_label DROP FOREIGN KEY FK_C9034BC8EA750E8');
        $this->addSql('DROP TABLE label');
        $this->addSql('DROP TABLE task_label');
    }
}
