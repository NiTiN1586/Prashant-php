<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211220152428 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add estimation_type and relations';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE estimation_type (id INT AUTO_INCREMENT NOT NULL, friendly_name VARCHAR(255) NOT NULL, handle VARCHAR(30) NOT NULL, display_order INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_estimation_type_createdAt (created_at), INDEX IDX_estimation_type_deletedAt (deleted_at), UNIQUE INDEX UK_estimation_type_handle (handle), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE task ADD estimation INT DEFAULT NULL');
        $this->addSql('ALTER TABLE witcher_project ADD estimation_type INT DEFAULT NULL');
        $this->addSql('ALTER TABLE witcher_project ADD CONSTRAINT FK_70D1B332608531AB FOREIGN KEY (estimation_type) REFERENCES estimation_type (id)');
        $this->addSql('CREATE INDEX IDX_witcher_project_estimationType ON witcher_project (estimation_type)');
    }

    public function postUp(Schema $schema): void
    {
        $query = <<<SQL
            INSERT IGNORE INTO estimation_type(friendly_name, handle, display_order, created_at, updated_at) VALUES
                ('Story point estimate', 'SP', 1, NOW(), NOW()),
                ('Time estimate', 'TIME', 2, NOW(), NOW());
            SQL;

        $this->connection->executeQuery($query);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE witcher_project DROP FOREIGN KEY FK_70D1B332608531AB');
        $this->addSql('DROP TABLE estimation_type');
        $this->addSql('ALTER TABLE task DROP estimation');
        $this->addSql('DROP INDEX IDX_witcher_project_estimationType ON witcher_project');
        $this->addSql('ALTER TABLE witcher_project DROP estimation_type');
    }
}
