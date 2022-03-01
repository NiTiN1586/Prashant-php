<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210519215756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add user invitation email ad google account email';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tblGoogleAccount ADD strEmail VARCHAR(40) NOT NULL');
        $this->addSql('ALTER TABLE tblUser ADD strInvitationEmail VARCHAR(40) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tblGoogleAccount DROP strEmail');
        $this->addSql('ALTER TABLE tblUser DROP strInvitationEmail');
    }
}
