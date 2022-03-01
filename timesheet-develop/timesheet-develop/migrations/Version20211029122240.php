<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211029122240 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create department table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, handle VARCHAR(50) NOT NULL, dtmCreatedAt DATETIME DEFAULT NULL, INDEX IDX_department_dtmCreatedAt (dtmCreatedAt), UNIQUE INDEX UK_department_handle (handle), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function postUp(Schema $schema): void
    {
        $query = <<<SQL
            INSERT IGNORE INTO department(name, handle, dtmCreatedAt) VALUES
                ('Development', 'DEVELOPMENT', NOW()),
                ('HR', 'HR', NOW()),
                ('Marketing', 'MARKETING', NOW()),
                ('Sale', 'SALE', NOW());
            SQL;

        $this->connection->executeQuery($query);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE department');
    }
}
