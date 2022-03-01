<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211027095237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create required indexes for the entity Task';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX IDX_tblTask_intDisplayOrder ON tblTask (intDisplayOrder)');
        $this->addSql('CREATE INDEX IDX_tblTask_dtmCreatedAt ON tblTask (dtmCreatedAt)');
        $this->addSql('ALTER TABLE tblTask RENAME INDEX uk_tbltask_handle TO UK_tblTask_strHandle');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_tblTask_intDisplayOrder ON tblTask');
        $this->addSql('DROP INDEX IDX_tblTask_dtmCreatedAt ON tblTask');
        $this->addSql('ALTER TABLE tblTask RENAME INDEX uk_tbltask_strhandle TO UK_tblTask_handle');
    }
}
