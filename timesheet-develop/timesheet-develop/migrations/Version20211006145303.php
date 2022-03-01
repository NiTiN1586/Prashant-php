<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211006145303 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Changing field from intJagaadUserId to intUserId';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tblWitcherUser CHANGE intjagaaduserid intUserId INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tblWitcherUser CHANGE intUserId intJagaadUserId INT NOT NULL');
    }
}
