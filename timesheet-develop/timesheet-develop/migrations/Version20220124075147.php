<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220124075147 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update indices for performance improvement WITCHER-152';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX IDX_task_witcherProject_createdBy ON task (witcher_project, created_by)');
        $this->addSql('CREATE UNIQUE INDEX UK_witcher_user_userId ON witcher_user (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_task_witcherProject_createdBy ON task');
        $this->addSql('DROP INDEX UK_witcher_user_userId ON witcher_user');
    }
}
