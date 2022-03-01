<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211027091439 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create required indexes for the entity WitcherUser';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX IDX_tblWitcherUser_bolActive ON tblWitcherUser (bolActive)');
        $this->addSql('CREATE INDEX IDX_tblWitcherUser_dtmCreatedAt ON tblWitcherUser (dtmCreatedAt)');
        $this->addSql('ALTER TABLE tblWitcherUser RENAME INDEX uniq_8144503626b328f0 TO UK_tblWitcherUser_intGitlabUserId');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_tblWitcherUser_bolActive ON tblWitcherUser');
        $this->addSql('DROP INDEX IDX_tblWitcherUser_dtmCreatedAt ON tblWitcherUser');
        $this->addSql('ALTER TABLE tblWitcherUser RENAME INDEX uk_tblwitcheruser_intgitlabuserid TO UNIQ_8144503626B328F0');
    }
}
