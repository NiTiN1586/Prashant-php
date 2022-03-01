<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211029140053 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create witcher_user_department table, add indices';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE witcher_user_department (witcher_user INT NOT NULL, department INT NOT NULL, INDEX IDX_7EE9E406102CBBE3 (witcher_user), INDEX IDX_7EE9E406CD1DE18A (department), PRIMARY KEY(witcher_user, department)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE witcher_user_department ADD CONSTRAINT FK_witcher_user_department_witcher_user FOREIGN KEY (witcher_user) REFERENCES tblWitcherUser (intId)');
        $this->addSql('ALTER TABLE witcher_user_department ADD CONSTRAINT FK_witcher_user_department_department FOREIGN KEY (department) REFERENCES department (id)');
        $this->addSql('ALTER TABLE tblWitcherUser ADD supervisor INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tblWitcherUser ADD CONSTRAINT FK_tblWitcher_supervisor FOREIGN KEY (supervisor) REFERENCES tblWitcherUser (intId)');
        $this->addSql('CREATE UNIQUE INDEX UK_tblWitcher_supervisor ON tblWitcherUser (supervisor)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE witcher_user_department');
        $this->addSql('ALTER TABLE tblWitcherUser DROP FOREIGN KEY FK_tblWitcher_supervisor');
        $this->addSql('DROP INDEX UK_tblWitcher_supervisor ON tblWitcherUser');
        $this->addSql('ALTER TABLE tblWitcherUser DROP supervisor');
    }
}
