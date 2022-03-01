<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211026182019 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create required indexes for the entity Client';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX IDX_tblClient_bolActive ON tblClient (bolActive)');
        $this->addSql('CREATE INDEX IDX_tblClient_dtmCreatedAt ON tblClient (dtmCreatedAt)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_tblClient_bolActive ON tblClient');
        $this->addSql('DROP INDEX IDX_tblClient_dtmCreatedAt ON tblClient');
    }
}
