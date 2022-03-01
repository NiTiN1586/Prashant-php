<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211217140043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Implement slug functionality for task';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_task_displayOrder ON task');
        $this->addSql('DROP INDEX UK_task_handle ON task');
        $this->addSql('ALTER TABLE task ADD external_key VARCHAR(30) DEFAULT NULL, DROP display_order, CHANGE friendly_name summary VARCHAR(255) NOT NULL, CHANGE handle slug VARCHAR(30) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UK_task_externalKey ON task (external_key)');
        $this->addSql('CREATE UNIQUE INDEX UK_task_slug ON task (slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UK_task_externalKey ON task');
        $this->addSql('DROP INDEX UK_task_slug ON task');
        $this->addSql('ALTER TABLE task ADD display_order INT DEFAULT NULL, DROP externalHandle, CHANGE summary friendly_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE slug handle VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE INDEX IDX_task_displayOrder ON task (display_order)');
        $this->addSql('CREATE UNIQUE INDEX UK_task_handle ON task (handle)');
    }
}
