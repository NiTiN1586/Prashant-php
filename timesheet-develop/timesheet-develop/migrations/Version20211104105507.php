<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211104105507 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove UK_tblWitcherUser_company_position from tblWitcherUser';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblWitcherUser DROP INDEX UK_tblWitcherUser_company_position, ADD INDEX IDX_tblWitcherUser_company_position (company_position)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblWitcherUser DROP INDEX IDX_tblWitcherUser_company_position, ADD UNIQUE INDEX UK_tblWitcherUser_company_position (company_position)');
    }
}
