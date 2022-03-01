<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211026153037 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create required indexes for the entity Activity';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX IDX_tblActivity_bolActive ON tblActivity (bolActive)');
        $this->addSql('CREATE INDEX IDX_tblActivity_dtmCreatedAt ON tblActivity (dtmCreatedAt)');
        $this->addSql('CREATE INDEX IDX_tblActivity_intDuration ON tblActivity (intDuration)');
        $this->addSql('CREATE INDEX IDX_tblActivity_dtmUpdatedAt ON tblActivity (dtmUpdatedAt)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_tblActivity_bolActive ON tblActivity');
        $this->addSql('DROP INDEX IDX_tblActivity_dtmCreatedAt ON tblActivity');
        $this->addSql('DROP INDEX IDX_tblActivity_intDuration ON tblActivity');
        $this->addSql('DROP INDEX IDX_tblActivity_dtmUpdatedAt ON tblActivity');
    }
}
