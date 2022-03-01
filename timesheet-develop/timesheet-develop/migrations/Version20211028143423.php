<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211028143423 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create company_position table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company_position (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, handle VARCHAR(50) NOT NULL, dtmCreatedAt DATETIME DEFAULT NULL, INDEX IDX_company_position_dtmCreatedAt (dtmCreatedAt), UNIQUE INDEX UK_company_position_handle (handle), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tblWitcherUser ADD company_position INT NOT NULL');
        $this->addSql('ALTER TABLE tblWitcherUser ADD CONSTRAINT FK_tblWitcherUser_company_position FOREIGN KEY (company_position) REFERENCES company_position (id)');
        $this->addSql('CREATE UNIQUE INDEX UK_tblWitcherUser_company_position ON tblWitcherUser (company_position)');
    }

    public function postUp(Schema $schema): void
    {
        $query = <<<SQL
            INSERT IGNORE INTO company_position(name, handle, dtmCreatedAt) VALUES
                ('CEO', 'CEO', NOW()),
                ('CTO', 'CTO', NOW()),
                ('CMO', 'CMO', NOW()),
                ('CFO', 'CFO', NOW()),
                ('Head of Engineering', 'HEAD_OF_ENGINEERING', NOW()),
                ('Lead Engineer', 'LEAD_ENGINEER', NOW());
            SQL;

        $this->connection->executeQuery($query);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblWitcherUser DROP FOREIGN KEY FK_tblWitcherUser_company_position');
        $this->addSql('DROP TABLE company_position');
        $this->addSql('DROP INDEX UK_tblWitcherUser_company_position ON tblWitcherUser');
        $this->addSql('ALTER TABLE tblWitcherUser DROP company_position');
    }
}
