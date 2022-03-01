<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220215115315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Amend estimation WIRCHER-387';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_activity_duration ON activity');
        $this->addSql('ALTER TABLE activity ADD estimation_time INT DEFAULT 0 NOT NULL, ADD estimation_sp INT DEFAULT 0 NOT NULL, DROP duration');
        $this->addSql('ALTER TABLE task ADD estimation_time INT DEFAULT 0 NOT NULL, ADD estimation_sp INT DEFAULT 0 NOT NULL, DROP estimation');
        $this->addSql('ALTER TABLE witcher_project DROP hours_in_sp');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity ADD duration INT NOT NULL, DROP estimation_time, DROP estimation_sp');
        $this->addSql('CREATE INDEX IDX_activity_duration ON activity (duration)');
        $this->addSql('ALTER TABLE task ADD estimation INT DEFAULT NULL, DROP estimation_time, DROP estimation_sp');
        $this->addSql('ALTER TABLE witcher_project ADD hours_in_sp INT DEFAULT NULL');
    }
}
