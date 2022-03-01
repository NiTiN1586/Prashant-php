<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220118160409 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Amend activity_type FK WITCHER-152';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A8F1A8CBB');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A8F1A8CBB FOREIGN KEY (activity_type) REFERENCES activity_type (id)');
        $this->addSql('CREATE INDEX IDX_role_handle ON role (handle)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A8F1A8CBB');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A8F1A8CBB FOREIGN KEY (activity_type) REFERENCES activity_type (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('DROP INDEX IDX_role_handle ON role');
    }
}
